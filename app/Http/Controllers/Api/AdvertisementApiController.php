<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enums\AdvertisementPaymentEnum;
use App\Http\Enums\PersonTypeEnum;
use App\Http\Enums\PriceTypeEnum;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\CategoryHelper;
use App\Http\Helpers\CommonHelper;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\LanguageHelper;
use App\Http\Requests\DeleteAdvertisementAdditionalPhotoRequest;
use App\Http\Requests\DeleteAdvertisementRequest;
use App\Http\Requests\EditAdvertisementRequest;
use App\Http\Requests\GetAdvertisementListForGuestRequest;
use App\Http\Requests\GetAdvertisementListForUserRequest;
use App\Http\Requests\GetAdvertisementsByCategoryRequest;
use App\Http\Requests\GetAdvertisementsByLocationRequest;
use App\Http\Requests\GetAdvertisementsBySearchRequest;
use App\Http\Requests\GetAdvertisementsTopRequest;
use App\Http\Requests\GetAuthorAllAdvertisementsRequest;
use App\Http\Requests\GetIntersectAdvertisementsRequest;
use App\Http\Requests\GetPersonContactsRequest;
use App\Http\Requests\GetStatisticsForAdvertisementRequest;
use App\Http\Requests\SetHideRequest;
use App\Http\Requests\SetPublishedRequest;
use App\Http\Requests\ShowAdvertisementRequest;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Http\Resources\EditAdvertisementResource;
use App\Http\Resources\ShowAdvertisementResource;
use App\Http\Validators\AdvertisementValidator;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Image;
use App\Models\User;
use App\Models\ViewContactUser;
use App\Repositories\AdvertisementRepository;
use App\Repositories\CategoryRepository;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


/**
 * Class AdvertisementApiController
 * @package App\Http\Controllers\Api
 * @group Advertisement
 */
class AdvertisementApiController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var AdvertisementValidator
     */
    private $validator;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    /**
     * @var AdvertisementRepository
     */
    private $advertisementRepository;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(CategoryRepository $categoryRepository,
                                CategoryHelper $categoryHelper,
                                AdvertisementValidator $validator,
                                GeoDBHelper $GeoDBHelper,
                                AdvertisementHelper $advertisementHelper,
                                AdvertisementRepository $advertisementRepository,
                                CommonHelper $commonHelper,
                                AuthHelper $authHelper,
                                LanguageHelper $languageHelper)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryHelper = $categoryHelper;
        $this->validator = $validator;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->advertisementHelper = $advertisementHelper;
        $this->advertisementRepository = $advertisementRepository;
        $this->commonHelper = $commonHelper;
        $this->authHelper = $authHelper;
        $this->languageHelper = $languageHelper;
    }

    /**
     * @authenticated
     *
     * @param StoreAdvertisementRequest $request
     * @return JsonResponse
     * @responseFile status=200 storage/responses/advertisement/storeAdvertisement.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function storeAdvertisement(StoreAdvertisementRequest $request): JsonResponse
    {
        $category = Category::where('id', $request->category_id)
            ->where('type', $request->advertisement_type)
            ->first();

        if (empty($category))
            return response()->json(['category_id' => [__('advertisement.Category not found')]], 422);

        if (!$this->validator->checkChildCategoryId($request->category_id, $request->child_category_id))
            return response()->json(['child_category_id' => [__('advertisement.Child category is invalid')]], 422);


        $formFields = $this->categoryRepository->getFormFields($request->category_id);
        $formFieldKeys = [];

        if ($formFields->isNotEmpty()) {
            $formFieldKeys = $formFields->pluck('key')->all();
            $checkValidator = $this->validator->storeAdvertisementValidator($formFieldKeys);

            if ($checkValidator->fails())
                return response()->json($checkValidator->errors()->getMessages(), 422);
        }

        $hashtags = [];
        if (!empty($request->hashtags)) {
            $hashtags = explode(',', $request->hashtags);
            $hashtags = array_map('trim', $hashtags);

            if (count($hashtags) > 20)
                return response()->json(['hashtags' => [__('validation.The number of hashtags cannot be more than 20')]], 422);
        }

        $user = auth()->user();
        $company = null;

        if ($request->person_type == PersonTypeEnum::COMPANY) {
            $company = $user->company;

            if (empty($company))
                return response()->json(['non_field_error' => [__('advertisement.Company not found')]], 400);
        }

        if (!empty($request->latitude) && !empty($request->longitude)) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        $path = null;
        if (!empty($request->file('sample')))
            $path = $this->advertisementHelper->uploadSample($request->file('sample'));

        $currencyId = $request->currency_id;
        if (empty($currencyId)) {
            $currency = Currency::where('code', 'RUB')->first();
            $currencyId = $currency->id;
        }

        $priceType = PriceTypeEnum::NO_BARGAINING_ID;
        $price = $request->price;
        // торг
        if (in_array($request->bargaining, [true, 'true', 1, '1'], true))
            $priceType = PriceTypeEnum::BARGAINING_ID;
        // Договорная
        if (in_array($request->negotiable, [true, 'true', 1, '1'], true)) {
            $priceType = PriceTypeEnum::NEGOTIABLE_ID;
            $price = 0;
        }

        try {
            $advertisement = Advertisement::create([
                'type' => $request->advertisement_type,
                'user_id' => $user->id,
                'company_id' => empty($company) ? null : $company->id,
                'category_id' => $request->category_id,
                'child_category_id' => $request->child_category_id,
                'title' => $request->title,
                'description' => $request->description,
                'price' => $price,
                'currency_id' => $currencyId,
                'price_type' => $priceType,
                'payment' => empty($request->payment) ? 0 : $request->payment,
                'hashtags' => $hashtags,
                'reach_audience' => $request->reach_audience,
                'travel_abroad' =>
                    $this->advertisementHelper->booleanToInteger('travel_abroad', $request->travel_abroad, $formFieldKeys),
                'ready_for_political_advertising' =>
                    $this->advertisementHelper->booleanToInteger('ready_for_political_advertising', $request->ready_for_political_advertising, $formFieldKeys),
                'photo_report' =>
                    $this->advertisementHelper->booleanToInteger('photo_report', $request->photo_report, $formFieldKeys),
                'make_and_place_advertising' =>
                    $this->advertisementHelper->booleanToInteger('make_and_place_advertising', $request->make_and_place_advertising, $formFieldKeys),
                'amount' => $request->amount,
                'length' => $request->length, //  Для всех категопий в мм
                'width' => $request->width, //  Для всех категопий в мм
                'video' => $request->video,
                'sample' => $path,
                'deadline_date' => $request->deadline_date,
                'link_page' => $request->link_page,
                'attendance' => $request->attendance,
                'date_of_the' => $request->date_of_the,
                'date_start' => $request->date_start,
                'date_finish' => $request->date_finish,
                'is_published' => false,
                'published_at' => null,
                'is_hide' =>  in_array($request->is_hide, [true, 'true', 1, '1'], true),
                'video_url' => $request->video_url,

                'country' => empty($data->country) ? '' : $data->country,
                'region' => empty($data->region) ? '' : $data->region,
                'city' => empty($data->city) ? '' : $data->city,
                'country_ext_code' => empty($data->countryCode) ? '' : $data->countryCode,
                'region_ext_code' => empty($data->regionCode) ? '' : $data->regionCode,
                'city_ext_code' => empty($data->id) ? '' : $data->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);


        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Advertisement was not stored') . $throwable->getMessage()]], 400);
        }

        try {
            $photoId = $this->advertisementHelper->uploadProfileAdditionalPhotos($advertisement, $request->file('images'));
            $advertisement->photo_id = $photoId;
            $advertisement->save();
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }

        return response()->json(['data' => ['advertisement_id' => $advertisement->id]]);
    }

    /**
     * @authenticated
     *
     * @param UpdateAdvertisementRequest $request
     * @return JsonResponse
     * @responseFile status=200 storage/responses/advertisement/updateAdvertisement.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function updateAdvertisement(UpdateAdvertisementRequest $request): JsonResponse
    {
        $user = auth()->user();
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $formFields = $this->categoryRepository->getFormFields($advertisement->category_id);
        $formFieldKeys = [];

        if ($formFields->isNotEmpty()) {
            $formFieldKeys = $formFields->pluck('key')->all();
            $checkValidator = $this->validator->storeAdvertisementValidator($formFieldKeys);

            if ($checkValidator->fails())
                return response()->json($checkValidator->errors()->getMessages(), 422);
        }


        if (isset($request->images)) {
            $files = $request->images;
            $imageCount = $advertisement->images()->count();
            $count = count($files);

            if (($imageCount + $count) > 5)
                return response()->json(["images" => [__("messages.Exceeded the number of images.Maximum number is : 5")]], 422);
        }


        $hashtags = [];
        if (!empty($request->hashtags)) {
            $hashtags = explode(',', $request->hashtags);
            $hashtags = array_map('trim', $hashtags);

            if (count($hashtags) > 20)
                return response()->json(['hashtags' => [__('validation.The number of hashtags cannot be more than 20')]], 422);
        }

        $isPublished = false;
        $isPublishedBool = in_array($request->is_published, [true, 'true', 1, '1'], true);
        // устанавливаем новую дату публикации. Если в объявление пустая
        $publishedAt = null;

        if ($isPublishedBool) {

            if (empty($user->phone_verified_at))
                return response()->json(['non_field_error' => [__('advertisement.To save an advertisement, you need to verify your phone')]], 400);

            $date = $this->commonHelper->getInvertMonth();

            // если объява не активна
            if ($advertisement->is_published == false ||
                empty($advertisement->published_at) ||
                ($advertisement->is_published && new DateTime($advertisement->published_at) < $date)
               ) {

                $isPublished = true;

                if (!$this->advertisementHelper->checkAdvertisementActive($advertisement, $user, $date))
                    return response()->json(['non_field_error' => [__('advertisement.Limit of active advertisements exceeded')]], 400);
            }

            $objDateTime = new DateTime('NOW');
            $publishedAt = $objDateTime->format('Y-m-d H:i:s');

        }

        // получаем страну регион и город если он поменялся
        if (!empty($request->latitude) &&
            $request->latitude != $advertisement->latitude &&
            !empty($request->longitude) &&
            $request->longitude != $advertisement->longitude
        ) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        $path = null;
        if (!empty($request->file('sample'))) {
            File::delete(public_path('storage/' . $advertisement->sample));
            $path = $this->advertisementHelper->uploadSample($request->file('sample'));
        }

        $priceType = PriceTypeEnum::NO_BARGAINING_ID;
        $price = $request->price;
        // торг
        if (in_array($request->bargaining, [true, 'true', 1, '1'], true))
            $priceType = PriceTypeEnum::BARGAINING_ID;
        // Договорная
        if (in_array($request->negotiable, [true, 'true', 1, '1'], true)) {
            $priceType = PriceTypeEnum::NEGOTIABLE_ID;
            // $price = 0;
        }

        $isPrice = false;
        if ($price != $advertisement->price)
            $isPrice = true;

        try {

            $advertisement->title = $request->title;
            $advertisement->description = $request->description;
            $advertisement->price = $price;
            if (!empty($request->currency_id))
                $advertisement->currency_id = $request->currency_id;

            $advertisement->price_type = $priceType;
            $advertisement->payment = empty($request->payment) ? 0 : $request->payment;
            $advertisement->hashtags = $hashtags;
            $advertisement->reach_audience = $request->reach_audience;
            $advertisement->travel_abroad =
                $this->advertisementHelper->booleanToInteger('travel_abroad', $request->travel_abroad, $formFieldKeys);
            $advertisement->ready_for_political_advertising =
                $this->advertisementHelper->booleanToInteger('ready_for_political_advertising', $request->ready_for_political_advertising, $formFieldKeys);
            $advertisement->photo_report =
                $this->advertisementHelper->booleanToInteger('photo_report', $request->photo_report, $formFieldKeys);
            $advertisement->make_and_place_advertising =
                $this->advertisementHelper->booleanToInteger('make_and_place_advertising', $request->make_and_place_advertising, $formFieldKeys);
            $advertisement->amount = $request->amount;
            $advertisement->length = $request->length; //  Для всех категопий в мм
            $advertisement->width = $request->width; //  Для всех категопий в мм
            $advertisement->video = $request->video;
            $advertisement->sample = $path;
            $advertisement->deadline_date = $request->deadline_date;
            $advertisement->link_page = $request->link_page;
            $advertisement->attendance = $request->attendance;
            $advertisement->date_of_the = $request->date_of_the;
            $advertisement->date_start = $request->date_start;
            $advertisement->date_finish = $request->date_finish;
            $advertisement->is_published = $isPublishedBool;
            $advertisement->published_at = $publishedAt;
            $advertisement->is_hide =  in_array($request->is_hide, [true, 'true', 1, '1'], true);
            $advertisement->video_url = $request->video_url;

            if (!empty($data->country))
                $advertisement->country = $data->country;
            if (!empty($data->region))
                $advertisement->region = $data->region;
            if (!empty($data->city))
                $advertisement->city = $data->city;

            if (!empty($data->countryCode))
                $advertisement->country_ext_code = $data->countryCode;
            if (!empty($data->regionCode))
                $advertisement->region_ext_code = $data->regionCode;
            if (!empty($data->id))
                $advertisement->city_ext_code = $data->id;

            $advertisement->latitude = $request->latitude;
            $advertisement->longitude = $request->longitude;

            // $advertisement->photo_id = null;
            $advertisement->save();

            /*
            Image::destroy($advertisement->images->map(
                function ($item) {
                    return $item->id;
                }
            ));


            $advertisement->images()->delete();
            */

            if (isset($request->images)) {
                $photoId = $this->advertisementHelper->uploadProfileAdditionalPhotos($advertisement, $request->file('images'));

                if (empty($advertisement->photo_id)) {
                    $advertisement->photo_id = $photoId;
                    $advertisement->save();
                }
            }


        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage() . $throwable->getMessage()]], 400);
        }

        $this->commonHelper->sendMailRecipientUser($advertisement, $isPublished, $isPrice);

        return response()->json(['data' => ['advertisement_id' => $advertisement->id]]);
    }

    /**
     * @authenticated
     *
     * @param SetPublishedRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function setPublished(SetPublishedRequest $request): JsonResponse
    {

        $user = auth()->user();

        if (empty($user->phone_verified_at))
            return response()->json(['non_field_error' => [__('advertisement.To active  an advertisement, you need to verify your phone in settings')]], 400);

        try {
            $advertisements = $this->advertisementHelper->checkCountIds($request->advertisement_ids, $user->id);
        } catch (\Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        $publishedAt = null;

        if ($request->is_published) {
            try {
                $this->advertisementHelper->checkAdvertisementslimit($advertisements, $user);
            } catch (\Exception $exception) {
                return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
            }

            $objDateTime = new DateTime('NOW');
            $publishedAt = $objDateTime->format('Y-m-d H:i:s');
        }

        $advertisements->each(function ($advertisement) use ($request, $publishedAt) {

            if ($request->is_published && $request->is_published != $advertisement->is_published)
                $this->commonHelper->sendMailRecipientUser($advertisement, true, false);

            $advertisement->is_published = $request->is_published;
            $advertisement->published_at = $publishedAt;
            $this->advertisementHelper->addPromotion($advertisement, $advertisement->is_published);

            $advertisement->save();

        });


        return response()->json(['data' => ['message' => 'update_advertisement']]);
    }

    /**
     * @authenticated
     *
     * @param SetHideRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function setHide(SetHideRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $advertisements = $this->advertisementHelper->checkCountIds($request->advertisement_ids, $user->id);
        } catch (\Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        $advertisements->each(function ($advertisement) use ($request) {
            $advertisement->is_hide = $request->is_hide;
            $this->advertisementHelper->addPromotion($advertisement, !$advertisement->is_hide);

            $advertisement->save();

        });

        return response()->json(['data' => ['message' => 'update_advertisement']]);
    }

    /**
     * @authenticated
     *
     * @param DeleteAdvertisementRequest $request
     * @return JsonResponse
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function deleteAdvertisement(DeleteAdvertisementRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $this->advertisementHelper->checkCountIds($request->advertisement_ids, $user->id);
        } catch (\Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        Advertisement::where('user_id', $user->id)
            ->whereIn('id', $request->advertisement_ids)
            ->delete();

        return response()->json(['data' => ['message' => 'delete_advertisement']]);
    }

    /**
     * @authenticated
     *
     * @param DeleteAdvertisementAdditionalPhotoRequest $request
     * @return JsonResponse
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function deleteAdvertisementAdditionalPhoto(DeleteAdvertisementAdditionalPhotoRequest $request): JsonResponse
    {
        $user = auth()->user();
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        try {

            $this->commonHelper->deleteAdditionalPhoto($advertisement, (int)$request->photo_id);
            return response()->json(["data" => ["message" => __("messages.Delete was successful")]]);
        } catch (\Exception $exception) {
            return response()->json(["images" => [__("messages.Failed to delete Additional photo"), $exception->getMessage()]], 400);
        }
    }

    /**
     *
     * @authenticated
     *
     * @param GetAdvertisementListForUserRequest $request
     * @return JsonResponse
     *
     * @queryParam person_type string required The person type private_person or company. Example: private_person
     * @queryParam is_publishe  boolean required The advertisement active or passive. Example: active
     * @queryParam search string The advertisement search hashtags or description or title. Example: "test"
     * @queryParam category_key integer The filter by category.  Example: "avtotransport"
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementListForUser.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementListForUser(GetAdvertisementListForUserRequest $request): JsonResponse
    {

        $advertisementList = $this->advertisementHelper->getAdvertisementListForUser(
            $request->person_type,
            $request->is_published,
            $request->search,
            $request->category_key
        );

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'person_type' => $request->person_type,
            'is_published' => $request->is_published,
            'category_key' => $request->category_key,
            'search' => $request->search
        ];


        $paginator = $this->commonHelper->paginate($advertisementList['advertisementList'], $prePage, $request->page, ['path' => url('/api/v1/getAdvertisementListForUser'), 'query' => $query]);
        $advertisementList['advertisementList'] = $paginator;

        return response()->json(['data' => $advertisementList]);
    }

    /**
     * @authenticated
     *
     * @param EditAdvertisementRequest $request
     * @return JsonResponse
     * @queryParam advertisement_id integer required The advertisement id.  Example: 7
     * @responseFile status=200 storage/responses/advertisement/editAdvertisement.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     *
     */
    public function editAdvertisement(EditAdvertisementRequest $request): JsonResponse
    {
        $user = auth()->user();
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->where('user_id', $user->id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $advertisement->person_type = PersonTypeEnum::PRIVATE_PERSON;
        if (!empty($advertisement->company_id))
            $advertisement->person_type = PersonTypeEnum::COMPANY;

        $advertisement->category = $this->categoryHelper->getCategory($advertisement->category_id);
        if (!empty($advertisement->child_category_id))
            $advertisement->child_category = $this->categoryHelper->getChildCategory($advertisement->child_category_id);

        $advertisement->formFields = $this->categoryHelper->getCategoryFormFields($advertisement->category_id, $advertisement->child_category_id, false);

        return (new EditAdvertisementResource($advertisement))->response();

    }

    /**
     * @authenticated
     *
     * @param ShowAdvertisementRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_id integer required The advertisement id.  Example: 7
     * @responseFile status=200 storage/responses/advertisement/showAdvertisement.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function showAdvertisement(ShowAdvertisementRequest $request): JsonResponse
    {
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        try {
            $advertisement = $this->advertisementHelper->getShowAdvertisement($advertisement);
        } catch (\Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        $advertisement->formFields = $this->categoryHelper->getCategoryFormFields($advertisement->category_id, $advertisement->child_category_id, false);

        return (new ShowAdvertisementResource($advertisement))->response();
    }

    /**
     * @return JsonResponse
     */
    public function getLastAdvertisements(): JsonResponse
    {
        $user = $this->authHelper->getUser();
        $lastAdvertisements = $this->advertisementHelper->getLastAdvertisements($user);

        return response()->json(['data' => $this->advertisementHelper->getSmallCardFormat($lastAdvertisements)]);
    }

    /**
     * @param GetIntersectAdvertisementsRequest $request
     *
     * @queryParam advertisement_id integer required The advertisement id.  Example: 7
     * @responseFile status=200 storage/responses/advertisement/getIntersectAdvertisements.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getIntersectAdvertisements(GetIntersectAdvertisementsRequest $request): JsonResponse
    {
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $intersectAdvertisements = $this->advertisementHelper->getIntersectAdvertisements($advertisement);

        return response()->json(['data' => $this->advertisementHelper->getSmallCardFormat($intersectAdvertisements)]);
    }

    /**
     * @return JsonResponse
     * @responseFile status=200 storage/responses/advertisement/getPaymentList.json
     */
    public function getPaymentList(): JsonResponse
    {
        $payment = [];
        foreach (AdvertisementPaymentEnum::ALL as $key => $value) {
            if ($key == 0)
                continue;

            $payment[] = ['id' => $key, 'name' => __('advertisement.' . $value)];
        }

        return response()->json(['data' => $payment]);
    }

    /**
     * @authenticated
     *
     * @param GetPersonContactsRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_id integer The advertisement id.  Example: 7
     * @queryParam user_id integer The advertisement id.  Example: 7
     * @queryParam company_id integer The advertisement id.  Example: 7
     * @responseFile status=200 storage/responses/advertisement/getPersonContacts.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getPersonContacts(GetPersonContactsRequest $request): JsonResponse
    {
        if (empty($request->advertisement_id) && $request->user_id && $request->company_id)
            return response()->json(['non_field_error' => [__('advertisement.Invalid input parameters')]], 422);

        $user = auth()->user();

        $viewContactUser = ViewContactUser::where('user_id', $user->id)->first();
        if (empty($viewContactUser) || ((int)$viewContactUser->view_contact < 1))
            return response()->json(['limit' => ['Limit is exceeded']], 400);

        if (!empty($request->advertisement_id)) {

            $advertisement = Advertisement::where('id', $request->advertisement_id)
                ->first();

            if (empty($advertisement))
                return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

            $advertisement->views_contact += 1;
            $advertisement->save();
        }

        $viewContactUser->view_contact -= 1;
        $viewContactUser->save();

        $userId = null;
        if (!empty($request->user_id))
            $userId = $request->user_id;

        $companyId = null;
        if (!empty($request->company_id))
            $companyId = $request->company_id;

        if (!empty($advertisement)) {
            $userId = $advertisement->user_id;
            $companyId = $advertisement->company_id;
        }

        $author = (object)['user_id' => $userId, 'company_id' => $companyId];

        if (!empty($companyId)) {
            $company = Company::find($companyId);
            $phone = !empty($company->phone) ? $company->phone : '';
        } else {
            $user = User::find($userId);
            $phone = !empty($user->phone) ? $user->phone : '';
        }

        return response()->json(['data' => [
            'phone' => $phone,
            'contacts' => $this->advertisementHelper->getPersonContacts($author, true),
            'social' => $this->advertisementHelper->getPersonSocial($author, true),
            ]
        ]);

    }

    /**
     * @param GetAdvertisementListForGuestRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_type string required The advertisement type performer or employer. Example: performer
     * @queryParam country_code string required Home page new by country.  Example: "UA"
     * @queryParam city_code string required Home page new by city.  Example: "112785"
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementListForGuest.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementListForGuest(GetAdvertisementListForGuestRequest $request): JsonResponse
    {
        $advertisements = $this->advertisementHelper->getAdvertisementListForGuest(
            $request->advertisement_type,
            $request->country_code,
            $request->city_code
        );

        return response()->json(['data' => $advertisements]);
    }

    /**
     * @param GetAuthorAllAdvertisementsRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_id integer The advertisement id.  Example: 7
     * @queryParam user_id integer The author user id.  Example: 7
     * @queryParam company_id integer The author company id.  Example: 7
     * @queryParam advertisement_id integer The advertisement id.  Example: 7
     * @queryParam category_key integer The filter by category.  Example: "avtotransport"
     * @queryParam advertisement_type string The  filter by  advertisement type performer or employer. Example: performer
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisement/getAuthorAllAdvertisements.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAuthorAllAdvertisements(GetAuthorAllAdvertisementsRequest $request): JsonResponse
    {
        if (empty($request->advertisement_id) && empty($request->user_id) && empty($request->company_id))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement id and user id and company id cannot be empty')]], 400);

        $advertisement = null;
        $authorAllAdvertisements = [
            'advertisement_list' => [],
            'categories' => [],
            'person' => [],
            'isSubscription' => false,
        ];

        if (!empty($request->advertisement_id)) {
            $advertisement = Advertisement::where('id', $request->advertisement_id)
                ->first();

            if (empty($advertisement))
                return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);
        } elseif (!empty($request->user_id)) {
            $advertisement = Advertisement::where('user_id', $request->user_id)
                ->first();

        } elseif (!empty($request->company_id)) {
            $advertisement = Advertisement::where('company_id', $request->company_id)
                ->first();
        }


        if (empty($advertisement)) {
            $authorAllAdvertisements['person'] = $this->advertisementHelper->getPersonById($request->user_id,  $request->company_id);
        } else {
            $authorAllAdvertisements = $this->advertisementHelper->getAuthorAllAdvertisements($advertisement, $request->category_key, $request->advertisement_type);
        }

        $prePage = 12;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'advertisement_id' => $request->advertisement_id,
            'category_key' => $request->category_key,
        ];

        $paginator = $this->commonHelper->paginate($authorAllAdvertisements['advertisement_list'], $prePage, $request->page, ['path' => url('/api/v1/getAuthorAllAdvertisements'), 'query' => $query]);

        $authorAllAdvertisements['advertisementList'] =
            $paginator->setCollection($this->advertisementHelper->getSmallCardFormat($paginator->getCollection()));

        unset($authorAllAdvertisements['advertisement_list']);

        return response()->json(['data' => $authorAllAdvertisements]);
    }

    /**
     * @param GetAdvertisementsByCategoryRequest $request
     * @return JsonResponse
     *
     * @queryParam category_key string required The filter by category.  Example: "avtotransport"
     *
     * @queryParam order string The order. Enum price_asc, price_desc, popular_asc, popular_desc. Example: "price_asc"
     * @queryParam person_type string The Advertisement person type. Enum private person, company.  Example: "company"
     * @queryParam advertisement_type string The Advertisement type. Enum performer, employer. Example: "performer"
     *
     * @queryParam child_category_key string The filter by category.  Example: "kuzov"
     * @queryParam payment_ids integer[]  The Advertisement payment type. Enum 1,2,3,4,5,6. Example:[1]
     * @queryParam price_from number  The Advertisement price from. Example:1.00
     * @queryParam price_to number  The Advertisement price to. Example:12.00
     * @queryParam amount_from integer  The Advertisement amount from. Example:1
     * @queryParam amount_to integer  The Advertisement amount to. Example:5
     * @queryParam length_from integer  The Advertisement length from. Example:2
     * @queryParam length_to integer  The Advertisement length to. Example:10
     * @queryParam width_from integer  The Advertisement width from. Example:2
     * @queryParam width_to integer  The Advertisement width to. Example:10
     * @queryParam reach_audience_from integer  The Advertisement reach audience from. Example:2
     * @queryParam reach_audience_to integer  The Advertisement reach audience to. Example:10
     * @queryParam travel_abroad boolean  The Advertisement travel abroad. Example:false
     * @queryParam ready_for_political_advertising boolean  The Advertisement ready for political advertising. Example:false
     * @queryParam photo_report boolean  The Advertisement photo report. Example:false
     * @queryParam make_and_place_advertising boolean  The Advertisement make and place advertising. Example:false
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementByCategory.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementsByCategory(GetAdvertisementsByCategoryRequest $request): JsonResponse
    {

        $query = [
            'category_key' => $request->category_key,
            'advertisement_type' => $request->advertisement_type,
            'person_type' => $request->person_type,
            'child_category_key' => $request->child_category_key,

            'order' => $request->order,
            'payment_ids' => $request->payment_ids,
            'price_from' => $request->price_from,
            'price_to' => $request->price_to,
            'amount_from' => $request->amount_from,
            'amount_to' => $request->amount_to,
            'length_from' => $request->length_from,
            'length_to' => $request->length_to,
            'width_from' => $request->width_from,
            'width_to' => $request->width_to,
            'reach_audience_from' => $request->reach_audience_from,
            'reach_audience_to' => $request->reach_audience_to,
            'travel_abroad' => $request->travel_abroad,
            'ready_for_political_advertising' => $request->ready_for_political_advertising,
            'photo_report' => $request->photo_report,
            'make_and_place_advertising' => $request->make_and_place_advertising,
        ];

        $advertisements = $this->advertisementHelper->getAdvertisementsByCategory($query);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;


        $paginator = $this->commonHelper->paginate($advertisements['advertisement_list'], $prePage, $request->page, ['path' => url('/api/v1/getAdvertisementsByCategory'), 'query' => $query]);

        $advertisements['advertisementList'] =
            $paginator->setCollection($this->advertisementHelper->getSmallCardFormat($paginator->getCollection()));

        unset($advertisements['advertisement_list']);

        return response()->json(['data' => $advertisements]);
    }

    /**
     * @param GetAdvertisementsByLocationRequest $request
     * @return JsonResponse
     *
     * @queryParam city_code string
     * @queryParam country_code string
     *
     * @queryParam order string The order. Enum price_asc, price_desc, popular_asc, popular_desc. Example: "price_asc"
     * @queryParam person_type string The Advertisement person type. Enum private person, company.  Example: "company"
     * @queryParam advertisement_type string The Advertisement type. Enum performer, employer. Example: "performer"
     *
     * @queryParam payment_ids integer[]  The Advertisement payment type. Enum 1,2,3,4,5,6. Example:[1]
     * @queryParam price_from number  The Advertisement price from. Example:1.00
     * @queryParam price_to number  The Advertisement price to. Example:12.00
     * @queryParam amount_from integer  The Advertisement amount from. Example:1
     * @queryParam amount_to integer  The Advertisement amount to. Example:5
     * @queryParam length_from integer  The Advertisement length from. Example:2
     * @queryParam length_to integer  The Advertisement length to. Example:10
     * @queryParam width_from integer  The Advertisement width from. Example:2
     * @queryParam width_to integer  The Advertisement width to. Example:10
     * @queryParam reach_audience_from integer  The Advertisement reach audience from. Example:2
     * @queryParam reach_audience_to integer  The Advertisement reach audience to. Example:10
     * @queryParam travel_abroad boolean  The Advertisement travel abroad. Example:false
     * @queryParam ready_for_political_advertising boolean  The Advertisement ready for political advertising. Example:false
     * @queryParam photo_report boolean  The Advertisement photo report. Example:false
     * @queryParam make_and_place_advertising boolean  The Advertisement make and place advertising. Example:false
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementsByLocation.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementsByLocation(GetAdvertisementsByLocationRequest $request): JsonResponse
    {
        $query = [
            'city_code' => $request->city_code,
            'country_code' => $request->country_code,
            'advertisement_type' => $request->advertisement_type,
            'person_type' => $request->person_type,
            'order' => $request->order,

            'payment_ids' => $request->payment_ids,
            'price_from' => $request->price_from,
            'price_to' => $request->price_to,
            'amount_from' => $request->amount_from,
            'amount_to' => $request->amount_to,
            'length_from' => $request->length_from,
            'length_to' => $request->length_to,
            'width_from' => $request->width_from,
            'width_to' => $request->width_to,
            'reach_audience_from' => $request->reach_audience_from,
            'reach_audience_to' => $request->reach_audience_to,
            'travel_abroad' => $request->travel_abroad,
            'ready_for_political_advertising' => $request->ready_for_political_advertising,
            'photo_report' => $request->photo_report,
            'make_and_place_advertising' => $request->make_and_place_advertising,
        ];

        $advertisements = $this->advertisementHelper->getAdvertisementsBySearch($query);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $paginator = $this->commonHelper->paginate($advertisements['advertisement_list'], $prePage, $request->page, ['path' => url('/api/v1/getAdvertisementsByLocation'), 'query' => $query]);

        $advertisements['advertisementList'] =
            $paginator->setCollection($this->advertisementHelper->getSmallCardFormat($paginator->getCollection()));

        unset($advertisements['advertisement_list']);

        return response()->json(['data' => $advertisements]);
    }

    /**
     * @param GetAdvertisementsBySearchRequest $request
     * @return JsonResponse
     *
     * @queryParam category_key string required The filter by category.  Example: "avtotransport"
     * @queryParam city_code string
     * @queryParam country_code string
     * @queryParam search string
     * @queryParam search_type string The search type. Enum advertisement, hashtags. Example: "advertisement"
     *
     * @queryParam order string The order. Enum price_asc, price_desc, popular_asc, popular_desc. Example: "price_asc"
     *
     * @queryParam person_type string The Advertisement person type. Enum private person, company.  Example: "company"
     * @queryParam advertisement_type string The Advertisement type. Enum performer, employer. Example: "performer"
     *
     * @queryParam payment_ids integer[]  The Advertisement payment type. Enum 1,2,3,4,5,6. Example:[1]
     * @queryParam price_from number  The Advertisement price from. Example:1.00
     * @queryParam price_to number  The Advertisement price to. Example:12.00
     * @queryParam amount_from integer  The Advertisement amount from. Example:1
     * @queryParam amount_to integer  The Advertisement amount to. Example:5
     * @queryParam length_from integer  The Advertisement length from. Example:2
     * @queryParam length_to integer  The Advertisement length to. Example:10
     * @queryParam width_from integer  The Advertisement width from. Example:2
     * @queryParam width_to integer  The Advertisement width to. Example:10
     * @queryParam reach_audience_from integer  The Advertisement reach audience from. Example:2
     * @queryParam reach_audience_to integer  The Advertisement reach audience to. Example:10
     * @queryParam travel_abroad boolean  The Advertisement travel abroad. Example:false
     * @queryParam ready_for_political_advertising boolean  The Advertisement ready for political advertising. Example:false
     * @queryParam photo_report boolean  The Advertisement photo report. Example:false
     * @queryParam make_and_place_advertising boolean  The Advertisement make and place advertising. Example:false
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementsBySearch.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementsBySearch(GetAdvertisementsBySearchRequest $request): JsonResponse
    {

        $query = [
            'search' => $request->search,
            'city_code' => $request->city_code,
            'country_code' => $request->country_code,
            'category_key' => $request->category_key,
            'search_type' => $request->search_type,

            'person_type' => $request->person_type,
            'advertisement_type' => $request->advertisement_type,
            'order' => $request->order,

            'payment_ids' => $request->payment_ids,
            'price_from' => $request->price_from,
            'price_to' => $request->price_to,
            'amount_from' => $request->amount_from,
            'amount_to' => $request->amount_to,
            'length_from' => $request->length_from,
            'length_to' => $request->length_to,
            'width_from' => $request->width_from,
            'width_to' => $request->width_to,
            'reach_audience_from' => $request->reach_audience_from,
            'reach_audience_to' => $request->reach_audience_to,
            'travel_abroad' => $request->travel_abroad,
            'ready_for_political_advertising' => $request->ready_for_political_advertising,
            'photo_report' => $request->photo_report,
            'make_and_place_advertising' => $request->make_and_place_advertising,
        ];

        $advertisements = $this->advertisementHelper->getAdvertisementsBySearch($query);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $paginator = $this->commonHelper->paginate($advertisements['advertisement_list'], $prePage, $request->page, ['path' => url('/api/v1/getAdvertisementsBySearch'), 'query' => $query]);

        $advertisements['advertisementList'] =
            $paginator->setCollection($this->advertisementHelper->getSmallCardFormat($paginator->getCollection()));

        unset($advertisements['advertisement_list']);

        return response()->json(['data' => $advertisements]);
    }

    /**
     * @param GetAdvertisementsTopRequest $request
     * @return JsonResponse
     *
     * @queryParam city_code string
     * @queryParam advertisement_type string The Advertisement type. Enum performer, employer. Example: "performer"
     * @responseFile status=200 storage/responses/advertisement/getAdvertisementsTop.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementsTop(GetAdvertisementsTopRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->advertisementHelper->getAdvertisementsTop($request->country_code, $request->advertisement_type)]);
    }

    /**
     * @param GetStatisticsForAdvertisementRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_id integer required The advertisement id.  Example: 7
     * @responseFile status=200 storage/responses/advertisement/getStatisticsForAdvertisement.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getStatisticsForAdvertisement(GetStatisticsForAdvertisementRequest $request)
    {
        $advertisement = Advertisement::where('id', $request->advertisement_id)
            ->first();

        if (empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $statistics = $this->advertisementHelper->getStatisticsForAdvertisement($request->advertisement_id);

        return response()->json(['data' => $statistics]);
    }

}
