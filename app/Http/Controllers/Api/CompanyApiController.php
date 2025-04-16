<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\CommonHelper;
use App\Http\Helpers\CompanyHelper;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\ImageHelper;
use App\Http\Requests\GetCompanyForGuestRequest;
use App\Http\Requests\getCompanyListForGuestRequest;
use App\Http\Requests\GetCompanyLogoListForGuestRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ImageResource;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\CompanyPhone;
use App\Models\CompanySocial;
use App\Models\Image;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Class CompanyApiController
 * @package App\Http\Controllers\Api
 * @group Company
 */
class CompanyApiController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CompanyHelper
     */
    private $companyHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;
    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    public function __construct(UserRepository $userRepository,
                                CompanyHelper $companyHelper,
                                AuthHelper $authHelper,
                                GeoDBHelper $GeoDBHelper,
                                CommonHelper $commonHelper,
                                AdvertisementHelper $advertisementHelper)
    {
        $this->userRepository = $userRepository;
        $this->companyHelper = $companyHelper;
        $this->authHelper = $authHelper;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->commonHelper = $commonHelper;
        $this->advertisementHelper = $advertisementHelper;
    }

    /**
     * @authenticated
     * @param StoreCompanyRequest $request
     * @return JsonResponse
     */
    public function storeCompany(StoreCompanyRequest $request): JsonResponse
    {

        if (!empty($request->phone) && !empty(Company::withTrashed()->where('phone', (int)$request->phone)->first()))
            return response()->json(["phone" => [__('validation.unique', ['attribute' => 'phone'])]], 400);

        if (!empty($request->email) && !empty(Company::withTrashed()->where('email', $request->email)->first()))
            return response()->json(["email" => [__('validation.unique', ['attribute' => 'email'])]], 400);

        $user = auth()->user();

        if ($user->company)
            return response()->json(["non_field_error" => [__("company.The company already exists")]], 400);

        if (!empty($request->hashtags)) {
            $hashtags = explode(',', $request->hashtags);

            if(count($hashtags) > 20)
                return response()->json(['hashtags' => [__('validation.The number of hashtags cannot be more than 20')]], 422);
        }

        if (!empty($request->latitude) && !empty($request->longitude)) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        try {
            $audioUrl = '';
            if (isset($request->audio))
                $audioUrl = $this->commonHelper->uploadedFile('companies/audio', $request->file("audio"));

            $documentUrl = '';
            if (isset($request->document))
                $documentUrl = $this->commonHelper->uploadedFile('companies/document', $request->file("document"));


            $company = Company::create([
                'owner_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'hashtags' => $request->hashtags,
                'phone' => empty((int)$request->phone) ? null : (int)$request->phone,
                'country' => empty($data->country) ? '' : $data->country,
                'region' => empty($data->region) ? '' : $data->region,
                'city' => empty($data->city) ? '' : $data->city,
                'country_id' => empty($data->countryCode) ? '' : $data->countryCode,
                'region_id' => empty($data->regionCode) ? '' : $data->regionCode,
                'city_id' => empty($data->id) ? '' : $data->id,
                'site_url' => $request->site_url,
                'video_url' => $request->video_url,
                'document_url' => $documentUrl,
                'audio_url' => $audioUrl,
            ]);

            if (!empty($request->social_media)) {
                foreach ($request->social_media as $social) {
                    if (empty($social["id"]) || empty($social['value']))
                        continue;

                    CompanySocial::create(['company_id' => $company->id, 'social_id' => $social["id"], 'values' => $social['value']]);
                }

            }

            if (!empty($request->contacts)) {
                foreach ($request->contacts as $contact) {
                    if (empty($contact["id"]) || empty($contact['value']))
                        continue;

                    CompanyContact::create(['company_id' => $company->id, 'contact_id' => $contact["id"], 'values' => $contact['value']]);
                }

            }

            if (!empty($request->additional_phones)) {

                foreach ($request->additional_phones as $phone) {
                    if (empty($phone))
                        continue;

                    CompanyPhone::create(['company_id' => $company->id, 'phone' => $phone]);
                }

            }

            if (isset($request->images)) {
                $photoId = $this->companyHelper->uploadProfileAdditionalPhotos_NoCache($company, $request->file('images'));
                $company->photo_id = $photoId;
            }

            if (isset($request->logo)) {
                $company->logo_id = ImageHelper::createPhotoFromRequest('companies/company_logo', $request->file('logo'))->id;
            }

            $company->save();

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [__("company.Failed to create a company") . $throwable->getMessage()]], 400);
        }
        return response()->json(['data' => ['company_id' => $company->id]]);
    }

    /**
     * @authenticated
     * @return JsonResponse
     */
    public function getCompany(): JsonResponse
    {
        $user = auth()->user();
        return response()->json(['data' => $this->companyHelper->getCompany($user)]);


        /**
         * @var User $user
         */
        /*
        $user = auth()->user();
        try {
            if (app()->getLocale() != 'ru' && $user->company->longitude && $user->company->latitude) {
                $data = $this->GeoDBHelper->generateGeoDbDataByCoordinates($user->company->longitude, $user->company->latitude);
                foreach ($data as $key => $value)
                    $user->company->$key = $value;
            }
            return (new CompanyResource($user->company))->response();
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [__("company.Failed to get a company")]], 400);
        }
        */

    }

    /**
     * @authenticated
     * @param UploadImageRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadCompanyMainPhoto(UploadImageRequest $request): JsonResponse
    {
        try {
            /**
             * @var $file UploadedFile
             * @var $user User
             * @var $company Company
             */
            $file = $request->file("image");
            $user = auth()->user();
            $company = $user->company;

            $this->companyHelper->changeAvatar($user->company, $file);
            return (new ImageResource($company->image))->response();
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [__("messages.Failed to upload main photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @param UploadImagesRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadCompanyAdditionalPhotos(UploadImagesRequest $request): JsonResponse
    {
        try {
            $files = $request->file("images");
            /**
             * @var $user User
             * @var $company Company
             */
            $user = auth()->user();
            $company = $user->company;
            $this->companyHelper->uploadAdditionalPhotos($user->company, $files);
            return ImageResource::collection($company->images)->response();
        } catch (\Exception $exception) {
            return response()->json(["non_field_error" => [__("messages.Failed to upload additional photo"), $exception->getMessage()]], 400);
        }
    }

    /**
     * @authenticated
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteCompanyMainPhoto(): JsonResponse
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $this->companyHelper->deleteMainPhoto($user->company);
            return response()->json(["data" => ["message" => __("messages.Delete was successful")]]);
        } catch (\Exception $exception) {
            return response()->json(["non_field_error" => [__("messages.Failed to delete main photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @param IdRequest $request
     * @return JsonResponse
     */
    public function deleteCompanyAdditionalPhoto(IdRequest $request): JsonResponse
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $this->commonHelper->deleteAdditionalPhoto($user->company, (int)$request->get('id'));
            return response()->json(["data" => ["message" => __("messages.Delete was successful")]]);
        } catch (\Exception $exception) {
            return response()->json(["images" => [__("messages.Failed to delete Additional photo"), $exception->getMessage()]], 400);
        }
    }

    /**
     * @authenticated
     * @param StoreCompanyRequest $request
     * @return JsonResponse
     */
    public function editCompany(StoreCompanyRequest $request): JsonResponse
    {

        $user = auth()->user();
        $company = $user->company;

        if (!empty($request->phone) && !empty(Company::withTrashed()->where('phone', (int)$request->phone)->where('id', '!=', $company->id)->first()))
            return response()->json(["phone" => [__('validation.unique', ['attribute' => 'phone'])]], 400);

        if (!empty($request->email) && !empty(Company::withTrashed()->where('email', $request->email)->where('id', '!=', $company->id)->first()))
            return response()->json(["email" => [__('validation.unique', ['attribute' => 'email'])]], 400);

        if (isset($request->images)) {
            $files = $request->images;
            $imageCount = $company->images()->count();
            $count = count($files);

            if (($imageCount + $count) > 10)
                return response()->json(["images" => [__("messages.Exceeded the number of images.Maximum number is : 3")]], 422);
        }



        if (!empty($request->hashtags)) {
            $hashtags = explode(',', $request->hashtags);

            if(count($hashtags) > 20)
                return response()->json(['hashtags' => [__('validation.The number of hashtags cannot be more than 20')]], 422);
        }

        // получаем страну регион и город если он поменялся
        if (!empty($request->latitude) &&
            $request->latitude != $company->latitude &&
            !empty($request->longitude) &&
            $request->longitude != $company->longitude
        ) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }


        try {

            CompanySocial::where('company_id', $company->id)->delete();
            if (!empty($request->social_media)) {
                foreach ($request->social_media as $social) {
                    if (empty($social["id"]) || empty($social['value']))
                        continue;

                    CompanySocial::create(['company_id' => $company->id, 'social_id' => $social["id"], 'values' => $social['value']]);
                }

            }

            CompanyContact::where('company_id', $company->id)->delete();
            if (!empty($request->contacts)) {
                foreach ($request->contacts as $contact) {
                    if (empty($contact["id"]) || empty($contact['value']))
                        continue;

                    CompanyContact::create(['company_id' => $company->id, 'contact_id' => $contact["id"], 'values' => $contact['value']]);
                }

            }

            CompanyPhone::where('company_id', $company->id)->delete();
            if (!empty($request->additional_phones)) {

                foreach ($request->additional_phones as $phone) {
                    if (empty($phone))
                        continue;

                    CompanyPhone::create(['company_id' => $company->id, 'phone' => $phone]);
                }

            }

            if (isset($request->audio)) {

                $path = json_decode($company->audio_url, true);
                if (!empty($path[0]['download_link']))
                    File::delete(public_path('storage/' . $path[0]['download_link']));

                $company->audio_url = $this->commonHelper->uploadedFile('companies/audio', $request->file("audio"));
            }

            if (isset($request->document)) {

                $path = json_decode($company->document_url, true);
                if (!empty($path[0]['download_link']))
                    File::delete(public_path('storage/' . $path[0]['download_link']));

                $company->document_url = $this->commonHelper->uploadedFile('companies/document', $request->file("document"));
            }

            $logoId = null;
            if (isset($request->logo)) {
                $logoId = $company->logo_id;
                $company->logo_id = ImageHelper::createPhotoFromRequest('companies/company_logo', $request->file('logo'))->id;
            }


            $company->name = $request->name;
            $company->email = $request->email;
            $company->description = $request->description;
            $company->latitude = $request->latitude;
            $company->longitude = $request->longitude;

            if (!empty($data->country))
                $company->country = $data->country;
            if (!empty($data->region))
                $company->region = $data->region;
            if (!empty($data->city))
                $company->city = $data->city;

            if (!empty($data->countryCode))
                $company->country_id = $data->countryCode;
            if (!empty($data->region_id))
                $company->region_id = $data->regionCode;
            if (!empty($data->id))
                $company->city_id = $data->id;

            $company->hashtags = $request->hashtags;
            $company->site_url = $request->site_url;
            $company->video_url = $request->video_url;

            $company->phone = empty((int)$request->phone) ? null : (int)$request->phone;

            $company->photo_id = null;

            $company->save();

            if (isset($request->images)) {

                /*
                Image::destroy($company->images->map(
                    function ($item) {
                        return $item->id;
                    }
                ));

                $company->images()->delete();
                */

                $photoId = $this->companyHelper->uploadProfileAdditionalPhotos_NoCache($company, $request->file('images'));
                $company->photo_id = $photoId;


            }

            if (!empty($logoId))
                Image::destroy($logoId);

            $company->save();

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }

        return response()->json(['data' => ['company_id' => $company->id]]);
    }


    /**
     * @authenticated
     * @return JsonResponse
     */
    public function deleteCompany()
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $company = $user->company;

            $pathAudio = json_decode($company->audio_url, true);
            if (!empty($pathAudio[0]['download_link']))
                File::delete(public_path('storage/' . $pathAudio[0]['download_link']));

            $pathDocument = json_decode($company->document_url, true);
            if (!empty($pathDocument[0]['download_link']))
                File::delete(public_path('storage/' . $pathDocument[0]['download_link']));

            $imageIds = $company->images->map(function ($item) {
                return $item->id;
            });

            $logoId = $company->logo_id;

            $company->forceDelete();


            if ($imageIds->isNotEmpty())
                Image::destroy($imageIds);

            if (!empty($logoId))
                Image::destroy($logoId);

        } catch (\Throwable $exception) {
            return response()->json(["non_field_error" => [__("company.Failed to delete company")]], 400);
        }
        return response()->json(["data" => [
            "message" => __("messages.Delete was successful"),
        ]]);
    }


    /**
     * @param GetCompanyLogoListForGuestRequest $request
     * @return JsonResponse
     *
     * @queryParam country_code string Home page new by country.  Example: "UA"
     * @queryParam city_code string Home page new by city.  Example: "112785"
     * @responseFile status=200 storage/responses/company/getCompanyLogoListForGuest.json
     *
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getCompanyLogoListForGuest(GetCompanyLogoListForGuestRequest $request): JsonResponse
    {
        $companyLogoList = $this->companyHelper->getCompanyLogoList($request->country_code, $request->city_code);

        return response()->json(['data' => $companyLogoList]);
    }

    /**
     * @param getCompanyListForGuestRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_type string required The advertisement type performer or employer. Example: performer
     * @queryParam search string The company search name or phone. Example: "test"
     * @queryParam country_code string Home page new by country.  Example: "UA"
     * @queryParam city_code string Home page new by city.  Example: "112785"
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/company/getCompanyListForGuest.json
     */
    public function getCompanyListForGuest(getCompanyListForGuestRequest $request): JsonResponse
    {
        $companyListForGuest = $this->companyHelper
            ->getCompanyListForGuest($request->advertisement_type, $request->search, $request->country_code, $request->city_code);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'advertisement_type' => $request->person_type,
            'search' => $request->search,
            'country_code' =>  $request->country_code,
            'city_code' => $request->city_code
        ];

        $paginator = $this->commonHelper->paginate($companyListForGuest, $prePage, $request->page, ['path' => url('/api/v1/getCompanyListForGuest'), 'query' => $query ]);

        $paginator->setCollection($this->companyHelper->getCompanyListForGuestFormat($paginator->getCollection()));

        return response()->json(['data' => $paginator]);
    }

    /**
     * @authenticated
     *
     * @param GetCompanyForGuestRequest $request
     * @return JsonResponse
     *
     * @queryParam company_id integer required The company id.  Example: 16
     * @queryParam category_key integer The filter by category.  Example: "avtotransport"
     * @queryParam advertisement_type string required The advertisement type performer or employer. Example: performer
     * @responseFile status=200 storage/responses/company/getCompanyForGuest.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getCompanyForGuest(GetCompanyForGuestRequest $request): JsonResponse
    {
        $company = Company::where('id', $request->company_id)
            ->first();

        if (empty($company))
            return response()->json(["non_field_error" => [__("company.Company not found")]], 400);


        $companyForGuest = $this->companyHelper->getCompanyForGuest($company);

        $advertisements = $this->advertisementHelper->getAdvertisementByCompany($company, $request->category_key, $request->advertisement_type);

        $companyForGuest['advertisementList'] =  $this->advertisementHelper->getSmallCardFormat($advertisements['advertisement_list']);
        $companyForGuest['categories'] = $advertisements['categories'];
        $companyForGuest['isSubscription'] = false;

        $user = $this->authHelper->getUser();
        if (!empty($user)) {
            $subscription = Subscription::where('recipient_user_id', $user->id)
                ->where('sender_company_id', $request->company_id)
                ->first();

            if (!empty($subscription))
                $companyForGuest['isSubscription'] = true;
        }


        return response()->json(['data' => $companyForGuest]);


    }


}
