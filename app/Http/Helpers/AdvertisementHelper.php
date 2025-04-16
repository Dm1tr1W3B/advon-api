<?php

namespace App\Http\Helpers;

use App\Http\Enums\AdvertisementPaymentEnum;
use App\Http\Enums\AdvertisementTypetEnum;
use App\Http\Enums\CategoryTypeEnum;
use App\Http\Enums\PersonTypeEnum;
use App\Http\Enums\PriceTypeEnum;
use App\Models\Advertisement;
use App\Models\AdvertisementComment;
use App\Models\AdvertisementFavorite;
use App\Models\AdvertisementView;
use App\Models\Category;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Image;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\AdvertisementRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use mysql_xdevapi\Result;
use PhpParser\Node\Expr\Empty_;


class AdvertisementHelper
{
    private static $maxCountOfAdditionalImages = 5;
    private $numberOfAdvertisementsCarousel = 16;

    /**
     * @var AdvertisementRepository
     */
    private $advertisementRepository;

    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    /**
     * @var CompanyHelper
     */
    private $companyHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;

    /**
     * @var UserHelper
     */
    private $userHelper;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;


    public function __construct(AdvertisementRepository $advertisementRepository,
                                CategoryHelper $categoryHelper,
                                GeoDBHelper $GeoDBHelper,
                                LanguageHelper $languageHelper,
                                AuthHelper $authHelper,
                                CompanyHelper $companyHelper,
                                UserHelper $userHelper,
                                UserRepository $userRepository,
                                CommonHelper $commonHelper,
                                TransactionBalanceHelper $transactionBalanceHelper)
    {
        $this->advertisementRepository = $advertisementRepository;
        $this->categoryHelper = $categoryHelper;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->languageHelper = $languageHelper;
        $this->authHelper = $authHelper;
        $this->companyHelper = $companyHelper;
        $this->userHelper = $userHelper;
        $this->userRepository = $userRepository;
        $this->commonHelper = $commonHelper;
        $this->transactionBalanceHelper = $transactionBalanceHelper;
    }

    /**
     * @param Advertisement $advertisemen
     * @param array $additionalPhotos
     * @throws Exception
     */
    public function uploadProfileAdditionalPhotos($advertisement, array $additionalPhotos)
    {
        $photos_id = [];
        foreach ($additionalPhotos as $additionalPhoto) {

            $photos_id[] = ImageHelper::createPhotoFromRequest('product_additional_photos', $additionalPhoto)->id;
        }
        $advertisement->images()->attach($photos_id);

        return $photos_id[0];
    }

    /**
     * @param UploadedFile $sample
     * @return string
     */
    public function uploadSample(UploadedFile $sample)
    {
        $date = new Carbon();
        $date->locale('en');
        $folder = 'additional_sample/' . $date->monthName . $date->year;

        if (!File::exists(public_path('storage/' . $folder))) {
            File::makeDirectory(public_path('storage/' . $folder), 0755, true);
        }

        $path = $sample->store('public/' . $folder);
        return Str::after($path, 'public');

    }

    /**
     * @param array $ids
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function checkCountIds(array $ids, int $userId)
    {
        $advertisements = $this->advertisementRepository->getAdvertisementsByIds($ids, $userId);

        if ($advertisements->count() != count($ids))
            throw  new Exception (__('advertisement.Advertisement not found'));

        return $advertisements;
    }

    /**
     * @param $advertisements
     * @param $user
     * @throws Exception
     */
    public function checkAdvertisementslimit($advertisements, $user)
    {
        $categoryIds = $advertisements->pluck('category_id')->unique()->values()->all();
        $categories = Category::whereIn('categories.id', $categoryIds)->get();

        $date = $this->commonHelper->getInvertMonth();
        $result = [];

        $advertisements->each(function ($advertisement) use ($date, $categories, &$result) {
            if (!empty($advertisement->published_at) && $advertisement->is_published) {
                $objDateStart = new DateTime($advertisement->published_at);

                if ($objDateStart > $date)
                    return;

            }

            $category = $categories->where('id', $advertisement->category_id)->first();

            if (empty($result[$category->key])) {
                $result[$category->key] = 1;
                return;
            }

            $result[$category->key] += 1;

        });

        $date = $this->commonHelper->getInvertMonth();
        $advertisementsActive = $this->userRepository->gettAdvertisementActive($user, $date);
        $categoriesByKeys = Category::whereIn('key', array_keys($result))->get();

        $limits = $this->userRepository->getUserCategories($user);
        foreach ($result as $category_key => $amount) {
            $limit = $limits->where('category_key', $category_key)->first();

            $advertisementActive = $advertisementsActive->where('category_key', $category_key)->first();
            if (!empty($advertisementActive))
                $amount += $advertisementActive->advertisement_active;

            $limitCategories = $categoriesByKeys->where('key', $category_key)->sum('limit');

            if (empty($limit) && $amount > $limitCategories)
                throw  new Exception (__('advertisement.Limit of active advertisements exceeded'));

            if (!empty($limit) && $amount > ($limit->limit + $limitCategories))
                throw  new Exception (__('advertisement.Limit of active advertisements exceeded'). ($limitCategories));
        }

    }

    /**
     * @param $advertisement
     * @param $user
     * @param $date
     * @return bool
     */
    public function checkAdvertisementActive($advertisement, $user, $date)
    {
        $category = Category::where('id', $advertisement->category_id)->first();

        $advertisementActive = $this->userRepository->gettAdvertisementActive($user, $date, $category->key)->first();

        if (empty($advertisementActive))
            return true;

        $limit = $category->limit + Category::where('id', '!=', $advertisement->category_id)
                ->where('key', $category->key)
                ->first()
                ->limit;

        $limits = $this->userRepository->getUserCategories($user, $category->key)->first();
        if (!empty($limits))
            $limit += $limits->limit;

        if ($advertisementActive->advertisement_active >= $limit)
            return false;

        return true;

    }


    /**
     * @param $advertisement
     * @return bool
     * @throws Exception
     */
    public function checkAdvertisementPublished($advertisement)
    {
        if (!empty($advertisement->published_at) && $advertisement->is_published) {
            $publishedAt = new DateTime($advertisement->published_at);
            $date = $this->commonHelper->getInvertMonth();

            if ($publishedAt > $date)
                return true;

        }

        return false;

    }

    /**
     * @param $advertisement
     * @param $isAdd
     * @throws Exception
     */
    public function addPromotion(&$advertisement, $isAdd)
    {
        $dateNow = new DateTime('NOW');
        $dateNowString = (new DateTime('NOW'))->format('Y-m-d');
        $dateNowString = $dateNowString . ' 23:59:59';
        // активация или открытие (НЕ скрытие)
        if ($isAdd) {

            // проверка объявления должно быть не скрытым и активным
            if ($advertisement->is_hide)
                return;

            $date = $this->commonHelper->getInvertMonth();
            if (!empty($advertisement->published_at) && $advertisement->is_published) {
                $objDateStart = new DateTime($advertisement->published_at);

                if ($objDateStart < $date)
                    return;

            }

            if ($advertisement->allocate_residue_days > 0) {
                $advertisement->is_allocate_at = $this->transactionBalanceHelper
                    ->addDays($advertisement->allocate_residue_days, $advertisement->is_allocate_at);

                $advertisement->allocate_residue_days = 0;
            }

            if ($advertisement->top_country_residue_days > 0) {
                $advertisement->is_top_country_at = $this->transactionBalanceHelper
                    ->addDays($advertisement->top_country_residue_days, $advertisement->is_top_country_at);

                $advertisement->top_country_residue_days = 0;
            }

            if ($advertisement->urgent_residue_days > 0) {
                $advertisement->is_urgent_at = $this->transactionBalanceHelper
                    ->addDays($advertisement->urgent_residue_days, $advertisement->is_urgent_at);

                $advertisement->urgent_residue_days = 0;
            }

            return;
        }

        // деактивация или скрытие
        $isAllocateAt = new DateTime($advertisement->is_allocate_at);
        if ($isAllocateAt > $dateNow) {
            $interval = $dateNow->diff($isAllocateAt);
            $advertisement->allocate_residue_days = (int) $interval->days;
            $advertisement->is_allocate_at = $dateNowString;
        }

        $isTopCountryAt = new DateTime($advertisement->is_top_country_at);
        if ($isTopCountryAt > $dateNow) {
            $interval = $dateNow->diff($isTopCountryAt);
            $advertisement->top_country_residue_days = (int) $interval->days;
            $advertisement->is_top_country_at = $dateNowString;
        }

        $isUrgentAt = new DateTime($advertisement->is_urgent_at);
        if ($isUrgentAt > $dateNow) {
            $interval = $dateNow->diff($isUrgentAt);
            $advertisement->urgent_residue_days = (int) $interval->days;
            $advertisement->is_urgent_at = $dateNowString;
        }

    }

    /**
     * @param string $personType
     * @param bool $isPublished
     * @param null $search
     * @param null $categoryId
     * @return array
     * @throws Exception
     */
    public function getAdvertisementListForUser(string $personType, bool $isPublished, $search = null, $categoryKey = null)
    {
        $user = auth()->user();

        $advertisementList = [];
        $categories = collect([]);
        $numberActive = 0;
        $numberPassive = 0;
        $isAdvertisementUser = false;
        $isAdvertisementCompany = false;

        $advertisement = Advertisement::where('user_id', $user->id)->first();
        if (!empty($advertisement)) {
            // company_id
            $isAdvertisementUser = true;
            $advertisementCompany = Advertisement::where('user_id', $user->id)
                ->whereNotNull('company_id')
                ->first();

            if (!empty($advertisementCompany))
                $isAdvertisementCompany = true;
        }

        $advertisements = $this->advertisementRepository->advertisementListForUser($personType, $user->id, $search, $categoryKey);

        // получаем все категории и валюты для перевода названий
        [$categoryPerformer, $categoryEmployer, $currencies] = $this->getCollects($advertisements);

        $advertisements->each(function ($advertisement) use (
            &$numberActive,
            &$numberPassive,
            &$advertisementList,
            $isPublished,
            $categoryPerformer,
            $categoryEmployer,
            &$categories,
            $currencies
        ) {

            $advertisement->image = $advertisement->photo_id ?
                asset(Storage::url(Image::find($advertisement->photo_id)->photo_url)) :
                url('storage/default/product.png');
            $advertisement->number_views = $advertisement->number_views == null ? 0 : $advertisement->number_views;

            // активные неактивные
            $isActive = false;
            $advertisement->date_start = '';
            $advertisement->date_finish = '';

            $numberPassive = $numberPassive + 1;

            if (!empty($advertisement->published_at) && $advertisement->is_published) {

                $objDateStart = new DateTime($advertisement->published_at);
                $advertisement->date_start = $objDateStart->format('d.m.Y');

                $objDateFinish = $objDateStart->add(new DateInterval('P1M'));
                $advertisement->date_finish = $objDateFinish->format('d.m.Y');

                $objDateTime = new DateTime('NOW');

                if ($objDateFinish > $objDateTime) {
                    $numberActive = $numberActive + 1;
                    $numberPassive = $numberPassive - 1;
                    $isActive = true;
                }

            }

            $advertisement->is_published = $isActive;
            if ($isActive != $isPublished)
                return;

            if ($advertisement->category_type == CategoryTypeEnum::PERFORMER) {

                $category = $categoryPerformer
                    ->where('category_id', $advertisement->category_id)
                    ->first();
            }

            if ($advertisement->category_type == CategoryTypeEnum::EMPLOYER) {

                $category = $categoryEmployer
                    ->where('category_id', $advertisement->category_id)
                    ->first();
            }

            $advertisement->category_name = $category->category_name;
            $categories->push($category);

            $advertisement->payment_name = AdvertisementPaymentEnum::NONE;
            if (!empty(AdvertisementPaymentEnum::ALL[(int)$advertisement->payment]) && (int)$advertisement->payment > 0)
                $advertisement->payment_name = __('advertisement.' . AdvertisementPaymentEnum::ALL[(int)$advertisement->payment]);

            $advertisement->translation_currency_code = $currencies[$advertisement->currency_code];
            $advertisement->price_type = __('advertisement.' . PriceTypeEnum::ALL[$advertisement->price_type]);

            // category
            $advertisement->category_name = '';
            $advertisement->category = null;
            if ($advertisement->category_type == CategoryTypeEnum::PERFORMER) {

                $advertisement->category = $categoryPerformer
                    ->where('category_id', $advertisement->category_id)
                    ->first();
            }

            if ($advertisement->category_type == CategoryTypeEnum::EMPLOYER) {

                $advertisement->category = $categoryEmployer
                    ->where('category_id', $advertisement->category_id)
                    ->first();
            }
            $advertisement->category_name = $advertisement->category->category_name;

            $advertisement->child_category = null;
            if (!empty($advertisement->child_category_id)) {
                $advertisement->child_category = $this->categoryHelper->getChildCategory($advertisement->child_category_id);
                $advertisement->category_name = $advertisement->child_category->child_category_name;
            }

            // todo возможно нужно кдл во в чате
            $advertisement->number_messages = AdvertisementComment::where('advertisement_id', $advertisement->advertisement_id)->count();

            $dateTime = new DateTime('NOW');

            if (!empty($advertisement->is_allocate_at)) {
                $isAllocateAt = new DateTime($advertisement->is_allocate_at);
                if($isAllocateAt  >= $dateTime) {
                    $advertisement->is_allocate = true;
                    $advertisement->is_allocate_at = $isAllocateAt->format('d.m.Y');
                } else {
                    $advertisement->is_allocate = false;
                    $advertisement->is_allocate_at = '';
                }
            }

            if (!empty($advertisement->is_top_country_at)) {
                $isTopCountryAt = new DateTime($advertisement->is_top_country_at);
                if($isTopCountryAt >= $dateTime) {
                    $advertisement->is_top_country = true;
                    $advertisement->is_top_country_at = $isTopCountryAt->format('d.m.Y');
                } else {
                    $advertisement->is_top_country = false;
                    $advertisement->is_top_country_at = '';
                }
            }

            if (!empty($advertisement->is_urgent_at)) {
                $isUrgentAt = new DateTime($advertisement->is_urgent_at);
                if($isUrgentAt >= $dateTime) {
                    $advertisement->is_urgent = true;
                    $advertisement->is_urgent_at = $isUrgentAt->format('d.m.Y');
                } else {
                    $advertisement->is_urgent = false;
                    $advertisement->is_urgent_at = '';
                }
            }


            unset($advertisement->photo_url,
                /// $advertisement->is_published,
                $advertisement->published_at,
                $advertisement->category_type,
                $advertisement->category_id,
                $advertisement->child_category_id,
                $advertisement->payment,
                $advertisement->category,
                $advertisement->child_category,
                $advertisement->currency_code
            );

            $advertisementList[] = $advertisement;
        });

        $limits = $this->userHelper->getAdvertisementLimit($user);
        $isLimit = false;
        foreach ($limits as $limit) {
            if($limit->advertisement_active >= $limit->limit) {
                $isLimit = true;
                break;
            }
        }


        return ['advertisementList' => $advertisementList,
            'categories' => $categories->unique('category_key')->values(),
            'number_active' => $numberActive,
            'number_passive' => $numberPassive,
            'is_advertisement_user' => $isAdvertisementUser,
            'is_advertisement_company' => $isAdvertisementCompany,
            'is_limit' => $isLimit,
        ];

    }

    /**
     * @param int $advertisementId
     */
    public function createAdvertisementView(int $advertisementId, $user = null)
    {
        try {
            AdvertisementView::create([
                'advertisement_id' => $advertisementId,
                'ip' => $this->getIp(),
                'user_id' => empty($user) ? null : $user->id,
            ]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
        }
    }

    /**
     * @param $user
     * @return Collection|\Illuminate\Support\Collection
     */
    public function getLastAdvertisements($user)
    {
        $ip = $this->getIp();
        $date = $this->commonHelper->getInvertMonth();
        return $this->advertisementRepository->getLastAdvertisement($user, $ip, $this->numberOfAdvertisementsCarousel, $date);
    }

    /**
     * @param $advertisement
     * @return \Illuminate\Support\Collection
     */
    public function getIntersectAdvertisements($advertisement)
    {
        $date = $this->commonHelper->getInvertMonth();
        $advertisements = $this->advertisementRepository->getIntersectAdvertisements($advertisement, $date)->shuffle();

        if ($advertisements->count() < $this->numberOfAdvertisementsCarousel)
            return $advertisements;

        $hashtags = $advertisement->hashtags;

        if (empty($hashtags) || !is_array($hashtags))
            return $advertisements->take($this->numberOfAdvertisementsCarousel);

        $intersectAdvertisements = collect([]);
        $categoryAdvertisements = collect([]);
        $intersectAdvertisementsCount = 0;


        $advertisements->each(function ($advertisement)
        use (&$intersectAdvertisements, &$intersectAdvertisementsCount, &$categoryAdvertisements, $hashtags) {

            if (!empty($advertisement->hashtags) &&
                is_array($advertisement->hashtags) &&
                !empty(array_intersect($advertisement->hashtags, $hashtags)) &&
                $intersectAdvertisementsCount <= $this->numberOfAdvertisementsCarousel
            ) {
                $intersectAdvertisementsCount = $intersectAdvertisementsCount + 1;
                $intersectAdvertisements->push($advertisement);
                return true;
            }

            $categoryAdvertisements->push($advertisement);
        });

        if ($intersectAdvertisementsCount == $this->numberOfAdvertisementsCarousel)
            return $intersectAdvertisements;

        $take = $this->numberOfAdvertisementsCarousel - $intersectAdvertisementsCount;

        $childCategoryAdvertisements = collect([]);
        if (!empty($advertisement->child_category_id)) {
            $childCategoryAdvertisements = $categoryAdvertisements->where('child_category_id', $advertisement->child_category_id)
                ->take($take)
                ->values();

            $take = $take - $childCategoryAdvertisements->count();
            $intersectAdvertisements = $intersectAdvertisements->merge($childCategoryAdvertisements);
        }

        if ($take == 0)
            return $intersectAdvertisements;


        if ($childCategoryAdvertisements->isNotEmpty()) {
            $ids = $childCategoryAdvertisements->pluck('advertisement_id');

            $intersectAdvertisements = $intersectAdvertisements->merge(
                $categoryAdvertisements->whereNotIn('advertisement_id', $ids)
                    ->take($take)
                    ->values()
            );

            return $intersectAdvertisements;
        }


        $intersectAdvertisements = $intersectAdvertisements->merge(
            $categoryAdvertisements->take($take)
                ->values()
        );

        return $intersectAdvertisements;
    }

    /**
     * @param $advertisements
     * @param null $number
     * @param null $countryCode
     * @param null $cityCode
     * @return \Illuminate\Support\Collection
     */
    public function getSmallCardFormat($advertisements, $number = null, $countryCode = null, $cityCode = null)
    {
        if ($advertisements->isEmpty())
            return $advertisements;

        [$categoryPerformer, $categoryEmployer, $currencies] = $this->getCollects($advertisements);

        $result = collect([]);

        $i = 0;

        $advertisementFavoriteIds = [];
        $user = $this->authHelper->getUser();

        if (!empty($user)) {
            $advertisementFavoriteIds = AdvertisementFavorite::where('user_id', $user->id)
                ->get()
                ->pluck('advertisement_id')
                ->all();
        }

        // list($country, $city) = $this->GeoDBHelper->getCountryAmdCityName($countryCode, $cityCode);

        $advertisements->each(function ($advertisement) use (
            $categoryPerformer,
            $categoryEmployer,
            $currencies,
            &$result,
            &$i,
            //$country,
            //$city,
            $countryCode,
            $cityCode,
            $number,
            $advertisementFavoriteIds
        ) {

            if (!empty($number) && ($i >= $number))
                return false;

            /*
            if (!empty($countryCode)  && !$this->checkCountry($advertisement, $countryCode, $country))
                return true;

            if (!empty($cityCode) && !$this->checkCity($advertisement, $cityCode, $city))
                return true;
           */

            $result->push($this->getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds));
            $i++;
        });

        return $result;
    }


    /**
     * @param $advertisement
     * @return array
     */
    public function getCity($advertisement)
    {
        $result = [
            'city' => '',
            'latitude' => 0,
            'longitude' => 0
        ];


        if (!empty($advertisement->advertisement_city) && App::getLocale() == 'ru') {
            return [
                'city' => $advertisement->advertisement_city,
                'latitude' => $advertisement->advertisement_latitude,
                'longitude' => $advertisement->advertisement_longitude
            ];
        }


        if (!empty($advertisement->advertisement_city_ext_code)) {
            try {
                $city = $this->GeoDBHelper->getCityName($advertisement->advertisement_city_ext_code, App::getLocale());

                if (!empty($city))
                    return [
                        'city' => $city,
                        'latitude' => $advertisement->advertisement_latitude,
                        'longitude' => $advertisement->advertisement_longitude
                    ];
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }
        /*
        if (!empty($advertisement->advertisement_latitude) && !empty($advertisement->advertisement_longitude)) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($advertisement->advertisement_latitude,
                    $advertisement->advertisement_longitude,
                    100,
                    App::getLocale())->first();

                if (!empty($data->city))
                    return $data->city;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        */

        if (!empty($advertisement->company_id)) {

            if (!empty($advertisement->company_city) && App::getLocale() == 'ru')
                return [
                    'city' => $advertisement->company_city,
                    'latitude' => $advertisement->company_latitude,
                    'longitude' => $advertisement->company_longitude
                ];

            if (!empty($advertisement->company_city_id)) {
                try {
                    $city = $this->GeoDBHelper->getCityName($advertisement->company_city_id, App::getLocale());

                    if (!empty($city))
                        return [
                            'city' => $city,
                            'latitude' => $advertisement->company_latitude,
                            'longitude' => $advertisement->company_longitude
                        ];
                } catch (Exception $exception) {
                    Log::critical("GEODB Error", $exception->getTrace());
                }
            }

            /*
            if (!empty($advertisement->company_latitude) && !empty($advertisement->company_longitude)) {

                try {
                    $data = $this->GeoDBHelper->getLocationNearbyCities($advertisement->company_latitude,
                        $advertisement->company_longitude,
                        100,
                        App::getLocale())->first();

                    if (!empty($data->city))
                        return $data->city;
                } catch (Exception $exception) {
                    Log::critical("GEODB Error", $exception->getTrace());
                }
            }
            */
        }

        if (!empty($advertisement->user_city) && App::getLocale() == 'ru')
            return [
                'city' => $advertisement->user_city,
                'latitude' => $advertisement->user_latitude,
                'longitude' => $advertisement->user_longitude
            ];


        if (!empty($advertisement->user_city_id)) {
            try {
                $city = $this->GeoDBHelper->getCityName($advertisement->user_city_id, App::getLocale());

                if (!empty($city))
                    return [
                        'city' => $city,
                        'latitude' => $advertisement->user_latitude,
                        'longitude' => $advertisement->user_longitude
                    ];
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        /*
        if (!empty($advertisement->user_latitude) && !empty($advertisement->user_longitude)) {

            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($advertisement->user_latitude,
                    $advertisement->user_longitude,
                    100,
                    App::getLocale())->first();

                if (!empty($data->city))
                    return $data->city;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }
        */

        return $result;
    }


    /**
     * @param $advertisement
     * @return mixed|string
     */
    public function getCountry($advertisement)
    {
        $country = '';


       if (!empty($advertisement->advertisement_country) && App::getLocale() == 'ru')
            return $advertisement->advertisement_country;

        if (!empty($advertisement->advertisement_country_ext_code)) {
            try {
                $country = $this->GeoDBHelper->getCountryName($advertisement->advertisement_country_ext_code, App::getLocale());

                if (!empty($country))
                    return $country;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        if (!empty($advertisement->company_id)) {

            if (!empty($advertisement->company_country) && App::getLocale() == 'ru')
                return $advertisement->company_country;

            if (!empty($advertisement->company_country_id)) {
                try {
                    $country = $this->GeoDBHelper->getCountryName($advertisement->company_country_id, App::getLocale());

                    if (!empty($country))
                        return $country;
                } catch (Exception $exception) {
                    Log::critical("GEODB Error", $exception->getTrace());
                }
            }

        }

        if (!empty($advertisement->user_country) && App::getLocale() == 'ru')
            return $advertisement->user_country;

        if (!empty($advertisement->user_country_id)) {
            try {
                $country = $this->GeoDBHelper->getCountryName($advertisement->user_country_id, App::getLocale());

                if (!empty($country))
                    return $country;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        return $country;
    }


    /**
     * @return mixed|string
     */
    public function getIp()
    {
        $value = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $value = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $value = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $value = $_SERVER['REMOTE_ADDR'];
        }

        return $value;
    }

    /**
     * @param string $kye
     * @param  $value
     * @param array $kyes
     * @return int
     */
    public function booleanToInteger(string $kye, $value, array $kyes)
    {
        if (!in_array($kye, $kyes))
            return 0;

        if (in_array($value, [true, 'true', 1, '1'], true))
            return 2;

        return 1;
    }

    /**
     * @param int $recipientUserId
     * @param string $personType
     * @return array
     */
    public function getSubscriptionList(int $recipientUserId, string $personType)
    {

        $subscriptions = Subscription::where('subscriptions.recipient_user_id', $recipientUserId);

        if ($personType == PersonTypeEnum::PRIVATE_PERSON) {
            $subscriptions = $subscriptions->join('users as u', 'u.id', '=', 'subscriptions.sender_user_id')
                ->select('subscriptions.id as subscription_id', 'u.*')
                ->whereNull('u.deleted_at');
        }

        if ($personType == PersonTypeEnum::COMPANY) {
            $subscriptions = $subscriptions->join('companies as c', 'c.id', '=', 'subscriptions.sender_company_id')
                ->select('subscriptions.id as subscription_id', 'c.*')
                ->whereNull('c.deleted_at');
        }

        $subscriptionList = [];
        $subscriptions = $subscriptions->get();
        if ($subscriptions->isEmpty())
            return $subscriptionList;


        $subscriptions->each(function ($subscription) use (&$subscriptionList, $personType) {
            $item = [];

            $item['subscription_id'] = $subscription->subscription_id;

            $phone = !empty($subscription->phone) ? $subscription->phone : '';
            $phone = Str::limit($phone, 2, 'XXXXXXX');
            if ($personType == PersonTypeEnum::PRIVATE_PERSON) {
                $item['id'] = $subscription->id;
                $item['name'] = $subscription->name;
                $item['avatar'] = (!empty($subscription->avatar) && $subscription->avatar != 'users/default.png') ?
                    asset(Storage::url($subscription->avatar)) : url('storage/default/user.png');
                $item['phone'] = $phone;
                $item['contacts'] = $this->userHelper->getUserContacts((int)$subscription->id, false);
                $item['city'] = $this->userHelper->getUserCity($subscription);
                $item['number_advertisement'] = Advertisement::where('user_id', $subscription->id)->count();

            }

            if ($personType == PersonTypeEnum::COMPANY) {
                $item['id'] = $subscription->id;
                $item['name'] = $subscription->name;
                $item['avatar'] = $subscription->logo_id ? asset(Storage::url(Image::find($subscription->logo_id)->photo_url)) : '';
                $item['phone'] = $phone;
                $item['contacts'] = $this->companyHelper->getCompanyContacts((int)$subscription->id, false);
                $item['city'] = $this->companyHelper->getCompanyCity($subscription);
                $item['number_advertisement'] = Advertisement::where('company_id', $subscription->id)->count();
            }

            $subscriptionList[] = $item;
        });

        return $subscriptionList;

    }

    /**
     * @param $advertisement
     * @param $contactShow
     * @return \Illuminate\Support\Collection
     */
    public function getPersonContacts($advertisement, $contactShow)
    {
        $contacts = collect([]);

        if (!empty($advertisement->company_id))
            $contacts = $this->companyHelper->getCompanyContacts($advertisement->company_id, $contactShow);

        if ($contacts->isNotEmpty())
            return $contacts;

        return $this->userHelper->getUserContacts($advertisement->user_id, $contactShow);
    }

    public function getPersonSocial($advertisement, $contactShow)
    {
        $contacts = collect([]);

        if (!empty($advertisement->company_id))
            $contacts = $this->companyHelper->getCompanySocial($advertisement->company_id, $contactShow);

        if ($contacts->isNotEmpty())
            return $contacts;

        return $this->userHelper->getUserSocial($advertisement->user_id, $contactShow);
    }

    /**
     * @param string $advertisementType
     * @param string $countryCode
     * @param string $cityCode
     * @return array
     */
    public function getAdvertisementListForGuest(string $advertisementType, string $countryCode, string $cityCode)
    {
        $carousels = [
            'carousel_city' => [],
            'carousel_country' => [],
            'carousel_performer' => [],
            'carousel_employer' => [],
        ];

        $categoryPerformer = $this->categoryHelper->getCategoryList(CategoryTypeEnum::PERFORMER);
        $categoryEmployer = $this->categoryHelper->getCategoryList(CategoryTypeEnum::EMPLOYER);
        $currencyCodes = Currency::get()->pluck('code')->all();
        $currencies = $this->languageHelper->getTranslations($currencyCodes, App::getLocale());

        $advertisementFavoriteIds = [];
        $user = $this->authHelper->getUser();

        if (!empty($user)) {
            $advertisementFavoriteIds = AdvertisementFavorite::where('user_id', $user->id)
                ->get()
                ->pluck('advertisement_id')
                ->all();
        }


        // мир EMPLOYER
        $filter = [
            'advertisement_type' => AdvertisementTypetEnum::EMPLOYER,
            'country_code' => null,
            'country_name' => null,
            'city_code' => null,
            'city_name' => null,
        ];

        $date = $this->commonHelper->getInvertMonth();

        $this->advertisementRepository
            ->getAdvertisementListForGuest($filter, $this->numberOfAdvertisementsCarousel, $date)->each(function ($advertisement) use (
                &$carousels,
                &$items,
                $categoryPerformer,
                $categoryEmployer,
                $currencies,
                $advertisementFavoriteIds
            ) {

                $item = $this->getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds);
                $items[$advertisement->advertisement_id] = $item;
                $carousels['carousel_employer'] [] = $item;
            });

        // мир PERFORMER
        $filter = [
            'advertisement_type' => AdvertisementTypetEnum::PERFORMER,
            'country_code' => null,
            'country_name' => null,
            'city_code' => null,
            'city_name' => null,
        ];

        $this->advertisementRepository
            ->getAdvertisementListForGuest($filter, $this->numberOfAdvertisementsCarousel,$date)
            ->each(function ($advertisement) use (
                &$carousels,
                &$items,
                $categoryPerformer,
                $categoryEmployer,
                $currencies,
                $advertisementFavoriteIds
            ) {

                $item = $this->getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds);
                $items[$advertisement->advertisement_id] = $item;
                $carousels['carousel_performer'] [] = $item;
            });

        [$country, $city] = $this->GeoDBHelper->getCountryAmdCityName($countryCode, $cityCode);

        // country
        $filter = [
            'advertisement_type' => $advertisementType,
            'country_code' => $countryCode,
            'country_name' => $country,
            'city_code' => null,
            'city_name' => null,
        ];

        $countryCount = 0;

        $this->advertisementRepository
            ->getAdvertisementListForGuest($filter, -1, $date)
            ->each(function ($advertisement) use (
                &$carousels,
                &$items,
                $categoryPerformer,
                $categoryEmployer,
                $currencies,
                $advertisementFavoriteIds,
                &$countryCount,
                $countryCode,
                $country
            ) {

                if ($countryCount > $this->numberOfAdvertisementsCarousel)
                    return false;


                // advertisement
                if (!empty($advertisement->advertisement_country_ext_code) &&
                    Str::lower($advertisement->advertisement_country_ext_code) != Str::lower($countryCode)
                ) {
                    return true;
                }

                if (!empty($advertisement->advertisement_country) &&
                    App::getLocale() == 'ru' &&
                    $country != '' &&
                    Str::lower($advertisement->advertisement_country) != Str::lower($country)
                ) {
                    return true;
                }

                // company
                if (empty($advertisement->advertisement_country_ext_code) &&
                    !empty($advertisement->company_country_id) &&
                    Str::lower($advertisement->company_country_id) != Str::lower($countryCode)
                ) {
                    return true;
                }


                if (empty($advertisement->advertisement_country) &&
                    !empty($advertisement->company_country) &&
                    App::getLocale() == 'ru' &&
                    $country != '' &&
                    Str::lower($advertisement->company_country) != Str::lower($country)
                ) {
                    return true;
                }


                // user
                if (empty($advertisement->advertisement_country_ext_code) &&
                    empty($advertisement->company_country_id) &&
                    !empty($advertisement->user_country_id) &&
                    Str::lower($advertisement->user_country_id) !=  Str::lower($countryCode)) {
                    return true;
                }

                if (empty($advertisement->advertisement_country) &&
                    empty($advertisement->company_country) &&
                    !empty($advertisement->user_country) &&
                    App::getLocale() == 'ru' &&
                    $country != '' &&
                    Str::lower($advertisement->user_country) !=  Str::lower($country)
                ) {
                    return true;
                }



                $countryCount = $countryCount + 1;

                if (!empty($items[$advertisement->advertisement_id])) {
                    $carousels['carousel_country'] [] = $items[$advertisement->advertisement_id];
                    return true;
                }

                $item = $this->getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds);
                $items[$advertisement->advertisement_id] = $item;
                $carousels['carousel_country'] [] = $item;
            });

        // city
        $filter = [
            'advertisement_type' => $advertisementType,
            'country_code' => $countryCode,
            'country_name' => $country,
            'city_code' => $cityCode,
            'city_name' => $city,
        ];

        $cityCount = 0;
        $this->advertisementRepository
            ->getAdvertisementListForGuest($filter, $this->numberOfAdvertisementsCarousel, $date)
            ->each(function ($advertisement) use (
                &$carousels,
                &$items,
                $categoryPerformer,
                $categoryEmployer,
                $currencies,
                $advertisementFavoriteIds,
                &$cityCount,
                $cityCode,
                $city
            ) {


                if ($cityCount > $this->numberOfAdvertisementsCarousel)
                    return false;

                // advertisement
                if (!empty($advertisement->advertisement_city_ext_code) &&
                    $advertisement->advertisement_city_ext_code != $cityCode
                ) {
                    return true;
                }

                if (!empty($advertisement->advertisement_city) &&
                    App::getLocale() == 'ru' &&
                    $city != '' &&
                    $advertisement->advertisement_city != $city
                ) {
                    return true;
                }

                // company
                if (empty($advertisement->advertisement_city_ext_code) &&
                    !empty($advertisement->company_city_id) &&
                    $advertisement->company_city_id != $cityCode
                ) {
                    return true;
                }

                if (empty($advertisement->advertisement_city) &&
                    !empty($advertisement->company_city) &&
                    App::getLocale() == 'ru' &&
                    $city != '' &&
                    $advertisement->company_city != $city
                ) {
                    return true;
                }

                // user
                if (empty($advertisement->advertisement_city_ext_code) &&
                    empty($advertisement->company_city_id) &&
                    !empty($advertisement->user_city_id) &&
                    $advertisement->user_city_id != $cityCode) {
                    return true;
                }

                if (empty($advertisement->advertisement_city) &&
                    empty($advertisement->company_city) &&
                    !empty($advertisement->user_city) &&
                    App::getLocale() == 'ru' &&
                    $city != '' &&
                    $advertisement->user_city != $city
                ) {
                    return true;
                }



                $cityCount = $cityCount + 1;

                if (!empty($items[$advertisement->advertisement_id])) {
                    $carousels['carousel_city'] [] = $items[$advertisement->advertisement_id];
                    return true;
                }

                $carousels['carousel_city'] [] = $this->getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds);
            });


        return $carousels;
    }

    /**
     * @param $advertisement
     * @return mixed
     */
    public function getShowAdvertisement($advertisement)
    {

        $contactShow = false;

        $user = $this->authHelper->getUser();

        if ($this->checkAdvertisementPublished($advertisement)) {
            $this->createAdvertisementView($advertisement->id, $user);

            $advertisement->views_total += 1;
            $advertisement->save();
        } else {

            if (empty($user) || $user->id !== $advertisement->user_id)
                throw new \Exception(__('advertisement.Advertisement not published (not active)'));

        }

        $advertisement->is_favorite = false;

        if (!empty($user)) {
            $advertisementFavorite = AdvertisementFavorite::where('advertisement_id', $advertisement->id)
                ->where('user_id', $user->id)
                ->first();

            if (!empty($advertisementFavorite))
                $advertisement->is_favorite = true;
        }


        $advertisement->number_views_all = $this->advertisementRepository->getNumberViews($advertisement->id);

        $objDateTime = new DateTime('NOW');
        $date = $objDateTime->format('Y-m-d') . ' 00:00:00';
        $advertisement->number_views_day = $this->advertisementRepository->getNumberViews($advertisement->id, $date);


        [$person, $advertisementUser, $advertisementCompany] = $this->getPerson($advertisement, $contactShow);
        $advertisement->person = $person;

        $keys = [
            'private_person',
            'company',
            'performer',
            'employer',
        ];

        $enumTranslations = $this->languageHelper->getTranslations($keys, App::getLocale());

        $advertisement->type = $enumTranslations[$advertisement->type];
        $advertisement->person_type = $enumTranslations[PersonTypeEnum::PRIVATE_PERSON];

        $date = $this->commonHelper->getInvertMonth();
        $numberAdvertisement = Advertisement::where('user_id', $advertisement->user_id)
            ->whereNull('company_id')
            ->where('is_published', true)
            ->where('published_at', '>=', $date)
            ->where('is_hide', false);

        if (!empty($advertisement->company_id)) {
            $numberAdvertisement = Advertisement::where('company_id', $advertisement->company_id)
                ->where('user_id', $advertisement->user_id)
                ->where('is_published', true)
                ->where('published_at', '>=', $date)
                ->where('is_hide', false);

            $advertisement->person_type = $enumTranslations[PersonTypeEnum::COMPANY];
        }

        $advertisement->number_advertisement = $numberAdvertisement->count();

        $currency = $advertisement->currency;
        $advertisement->currency = $this->languageHelper->getTranslations([$currency->code], App::getLocale())[$currency->code];

        $advertisement->advertisement_latitude = $advertisement->latitude;
        $advertisement->advertisement_longitude = $advertisement->longitude;
        $advertisement->advertisement_country = $advertisement->country;
        $advertisement->advertisement_country_ext_code = $advertisement->country_ext_code;
        $advertisement->advertisement_city = $advertisement->city;
        $advertisement->advertisement_city_ext_code = $advertisement->city_ext_code;

        $advertisement->user_latitude = $advertisementUser->latitude;
        $advertisement->user_longitude = $advertisementUser->longitude;
        $advertisement->user_country = $advertisementUser->country;
        $advertisement->user_country_id = $advertisementUser->country_id;
        $advertisement->user_city = $advertisementUser->city;
        $advertisement->user_city_id = $advertisementUser->city_id;

        $advertisement->company_latitude = null;
        $advertisement->company_longitude = null;
        $advertisement->company_country = null;
        $advertisement->company_country_id = null;
        $advertisement->company_city = null;
        $advertisement->company_city_id = null;

        if (!empty($advertisementCompany)) {
            $advertisement->company_latitude = $advertisementCompany->latitude;
            $advertisement->company_longitude = $advertisementCompany->longitude;
            $advertisement->company_country = $advertisementCompany->country;
            $advertisement->company_country_id = $advertisementCompany->country_id;
            $advertisement->company_city = $advertisementCompany->city;
            $advertisement->company_city_id = $advertisementCompany->city_id;
        }

        $advertisement->city = $this->getCity($advertisement)['city'];
        $advertisement->country = $this->getCountry($advertisement);

        $advertisement->category = $this->categoryHelper->getCategory($advertisement->category_id, true);

        if (!empty($advertisement->child_category_id))
            $advertisement->child_category = $this->categoryHelper->getChildCategory($advertisement->child_category_id, true);

        return $advertisement;
    }


    /**
     * @param $advertisement
     * @param null $categoryKey
     * @param null $advertisementType
     * @return array|mixed
     */
    public function getAuthorAllAdvertisements($advertisement, $categoryKey = null, $advertisementType = null)
    {
        $author = (object)['user_id' => $advertisement->user_id, 'company_id' => $advertisement->company_id];

        $result = $this->getAdvertisementsAndCategories($author, $categoryKey, $advertisementType);

        $user = $this->authHelper->getUser();
        $contactShow = false;

        $result['person'] = $this->getPerson($advertisement, $contactShow)[0];

        $result['isSubscription'] = false;

        $user = $this->authHelper->getUser();
        if (empty($user))
            return $result;

        $subscription = Subscription::where('recipient_user_id', $user->id);

        if (!empty($advertisement->company_id)) {
            $subscription = $subscription->where('sender_company_id', $advertisement->company_id)
                ->first();

            if (!empty($subscription))
                $result['isSubscription'] = true;

            return $result;
        }

        $subscription = $subscription->where('sender_user_id', $advertisement->user_id)
            ->first();

        if (!empty($subscription))
            $result['isSubscription'] = true;

        return $result;
    }

    /**
     * @param $company
     * @param $categoryKey
     * @param $advertisementType
     * @return array
     */
    public function getAdvertisementByCompany($company, $categoryKey = null, $advertisementType = null): array
    {
        $author = (object)['user_id' => $company->owner_id, 'company_id' => $company->id];
        return $this->getAdvertisementsAndCategories($author, $categoryKey, $advertisementType);
    }

    /**
     * @param array $query
     * @return array
     */
    public function getAdvertisementsByCategory(array $query)
    {
        $result = [
            'category' => [],
            'childCategories' => [],
            'advertisement_list' => [],
        ];

        $advertisementTypeDefault = empty($query['advertisement_type']) ?
            AdvertisementTypetEnum::PERFORMER : $query['advertisement_type'];

        $categories = $this->categoryHelper->getCategoriesByKey($query['category_key']);
        if ($categories->isEmpty())
            return $result;

        $category = $categories->where('type', $advertisementTypeDefault)->first();

        // $result['category_form_fields'] = $this->categoryHelper->getCategoryFormFieldsByCategorId($category->category_id);

        $childCategories = $this->categoryHelper->getChildCategoriesByKey($query['category_key']);

        $childCategoryIds = [];
        if (!empty($childCategories) && !empty($query['child_category_key'])) {

            $childCategory = Arr::first($childCategories, function ($value, $key) use ($query) {
                return $value['child_category_key'] == $query['child_category_key'];
            });

            if (!empty($childCategory['ids']))
                $childCategoryIds = $childCategory['ids'];
        }
        $date = $this->commonHelper->getInvertMonth();
        $advertisements = $this->advertisementRepository->getAdvertisementsByCategory($childCategoryIds, $query, $date);


        $result['category'] = [
            'category_key' => $category->key,
            'photo_url' => $category->photo_url,
            'category_name' => $category->category_name,
            'category_title_ograph' => $category->title_ograph,
            'count' => $advertisements->count(),
        ];

        foreach ($childCategories as &$childCategory) {
            $childCategory['count'] = $advertisements
                ->whereIn('child_category_id', $childCategory['ids'])->count();
            unset($childCategory['ids']);
        }

        $result['childCategories'] = $childCategories;

        $result['advertisement_list'] = $advertisements;

        return $result;
    }

    /**
     * @param array $query
     * @return mixed
     */
    public function getAdvertisementsBySearch(array $query)
    {

        [$country, $city] = $this->GeoDBHelper->getCountryAmdCityName($query['country_code'], $query['city_code']);
        $query['country_name'] = $country;
        $query['city_name'] = $city;

        $date = $this->commonHelper->getInvertMonth();
        $advertisements = $this->advertisementRepository->getAdvertisementsBySearch($query, $date);
        $advertisementList = collect([]);

        $advertisements->each(function ($advertisement) use (&$advertisementList, $query) {
            if (!empty($query['country_code'])) {
                // advertisement
                if (!empty($advertisement->advertisement_country_ext_code) &&
                    Str::lower($advertisement->advertisement_country_ext_code) != Str::lower($query['country_code'])
                ) {
                    return true;
                }

                if (!empty($advertisement->advertisement_country) &&
                    App::getLocale() == 'ru' &&
                    $query['country_name'] != '' &&
                    Str::lower($advertisement->advertisement_country) != Str::lower($query['country_name'])
                ) {
                    return true;
                }

                // company
                if (empty($advertisement->advertisement_country_ext_code) &&
                    !empty($advertisement->company_country_id) &&
                    Str::lower($advertisement->company_country_id) != Str::lower($query['country_code'])
                ) {
                    return true;
                }


                if (empty($advertisement->advertisement_country) &&
                    !empty($advertisement->company_country) &&
                    App::getLocale() == 'ru' &&
                    $query['country_name'] != '' &&
                    Str::lower($advertisement->company_country) != Str::lower($query['country_name'])
                ) {
                    return true;
                }


                // user
                if (empty($advertisement->advertisement_country_ext_code) &&
                    empty($advertisement->company_country_id) &&
                    !empty($advertisement->user_country_id) &&
                    Str::lower($advertisement->user_country_id) !=  Str::lower($query['country_code'])) {
                    return true;
                }

                if (empty($advertisement->advertisement_country) &&
                    empty($advertisement->company_country) &&
                    !empty($advertisement->user_country) &&
                    App::getLocale() == 'ru' &&
                    $query['country_name'] != '' &&
                    Str::lower($advertisement->user_country) !=  Str::lower($query['country_name'])
                ) {
                    return true;
                }

            }

            if (!empty($query['city_code'])) {
                // advertisement
                if (!empty($advertisement->advertisement_city_ext_code) &&
                    $advertisement->advertisement_city_ext_code != $query['city_code']
                ) {
                    return true;
                }

                if (!empty($advertisement->advertisement_city) &&
                    App::getLocale() == 'ru' &&
                    $query['city_name'] != '' &&
                    $advertisement->advertisement_city != $query['city_name']
                ) {
                    return true;
                }

                // company
                if (empty($advertisement->advertisement_city_ext_code) &&
                    !empty($advertisement->company_city_id) &&
                    $advertisement->company_city_id != $query['city_code']
                ) {
                    return true;
                }

                if (empty($advertisement->advertisement_city) &&
                    !empty($advertisement->company_city) &&
                    App::getLocale() == 'ru' &&
                    $query['city_name'] != '' &&
                    $advertisement->company_city != $query['city_name']
                ) {
                    return true;
                }

                // user
                if (empty($advertisement->advertisement_city_ext_code) &&
                    empty($advertisement->company_city_id) &&
                    !empty($advertisement->user_city_id) &&
                    $advertisement->user_city_id != $query['city_code']) {
                    return true;
                }

                if (empty($advertisement->advertisement_city) &&
                    empty($advertisement->company_city) &&
                    !empty($advertisement->user_city) &&
                    App::getLocale() == 'ru' &&
                    $query['city_name'] != '' &&
                    $advertisement->user_city != $query['city_name']
                ) {
                    return true;
                }
            }

            $advertisementList->push($advertisement);
        });

        $result['advertisement_list'] = $advertisementList;

        $ids = $advertisements->pluck('category_id')->unique()->values()->all();
        $categories = $this->categoryHelper->getCategoriesByIds($ids);

        $result['categories'] = $categories->unique('category_key')
            ->map(function ($category) use ($categories, $advertisements) {

                $ids = $categories->where('category_key', $category->category_key)
                    ->pluck('category_id')
                    ->all();

                $category->count = $advertisements->whereIn('category_id', $ids)->count();

                unset($category->category_id);

                return $category;
            })
            ->values();

        return $result;

    }

    /**
     * @param string $countryCode
     * @param null $advertisementType
     * @return \Illuminate\Support\Collection
     */
    public function getAdvertisementsTop(string $countryCode, $advertisementType = null)
    {
        $countryName = $this->GeoDBHelper->getCountryName($countryCode);
        $date = $this->commonHelper->getInvertMonth();
        $advertisements = $this->advertisementRepository->getAdvertisementsTop($countryCode, $countryName, $this->numberOfAdvertisementsCarousel,  $date, $advertisementType);
        return $this->getSmallCardFormat($advertisements);
    }

    /**
     * @param $advertisementId
     * @return mixed
     */
    public function getStatisticsForAdvertisement($advertisementId)
    {
        $date = new DateTime('NOW');
        $date->sub(new DateInterval('P30D'));

        $advertisementView = AdvertisementView::select(DB::raw("DATE(created_at) as date"), DB::raw('count(*) as views'))
            ->where('advertisement_id', $advertisementId)
            ->where('created_at', '>=', $date)
            ->orderBy('date', 'DESC')
            ->groupBy('date')
            ->get();

        //$advertisementView = $this->advertisementRepository->addUserSettings($advertisementView)
            //->get();

        $statistics['all'] = $advertisementView->sum('views');

        $statistics['today'] = 0;
        $today = new DateTime('NOW');
        $todayStatistic = $advertisementView->where('date', $today->format('Y-m-d'))->first();

        if (!empty($todayStatistic))
            $statistics['today'] = $todayStatistic->views;

        for ($i = 1; $i <= 30; $i++) {

            if ($i == 1) {
                $day = $today;
            } else {
                $day = $today
                    ->sub(new DateInterval('P1D'));
            }

            $dayStatistic = $advertisementView->where('date', $day->format('Y-m-d'))->first();

            if (empty($dayStatistic))
                $advertisementView->push(['date' => $day->format('d-m-Y'), 'views' => 0]);


        }

        $statistics['statistics'] = $advertisementView
            ->map(function ($item) {
                $date = new DateTime($item['date']);
                $item['date'] = $date->format('d-m-Y');
                return $item;
            })
            ->sortByDesc(function ($item) {
                $date = new DateTime($item['date']);
                return $date->getTimestamp();
            })
            ->values();

        return $statistics;
    }

    /**
     * @param $userId
     * @param $companyId
     * @return array
     */
    public function getPersonById($userId, $companyId)
    {
        $person = [];

        $user = $this->authHelper->getUser();
        $contactShow = false;

        $author = (object)['user_id' => $userId, 'company_id' => $companyId];
        $personContacts = $this->getPersonContacts($author, $contactShow);
        $social = $this->getPersonSocial($author, $contactShow);

        if (!empty($companyId)) {

            $company = Company::find($companyId);

            $phone = !empty($company->phone) ? $company->phone : '';
            $person = [
                'id' => $company->id,
                'name' => $company->name,
                'avatar' => $company->logo_id ?
                    asset(Storage::url(Image::find($company->logo_id)->photo_url)) : url('storage/default/company.png'),
                'phone' => $contactShow ? $phone : Str::limit($phone, 2, 'XXXXXXX'),
                'contacts' => $personContacts,
                'social' => $social,
                'created_at' => $company->created_at->format('d.m.Y'),
                'company_is_verification' => $company->is_verification,
            ];

        }

        if (!empty($userId)) {
            $user = User::find($userId);

            $phone = !empty($user->phone) ? $user->phone : '';
            $person = [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => (!empty($user->avatar) && $user->avatar != 'users/default.png') ?
                    asset(Storage::url($user->avatar)) : url('storage/default/user.png'),
                'phone' => $contactShow ? $phone : Str::limit($phone, 2, 'XXXXXXX'),
                'contacts' => $personContacts,
                'social' => $social,
                'created_at' => $user->created_at->format('d.m.Y'),
                'company_is_verification' => false,
            ];
        }

        return $person;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCountries()
    {
        $countries = collect([]);

        $date = $this->commonHelper->getInvertMonth();
        $this->advertisementRepository->getAdvertisementListForCountries($date)
            ->each(function ($advertisement) use(&$countries) {

                $country = $this->getCountryAndCode($advertisement);
                if (!empty($country->name) && !empty($country->code)) {
                    if (!$countries
                        ->where('code', $country->code)
                        ->first())
                        $countries->push($country);
                }
            });


        return $countries;
    }


    public function getCountryAndCode($advertisement)
    {
        $result = (object)[
            'name' => '',
            'code' => '',
        ];

        $countryCodes = [
            'Россия' => 'RU',
            'Украина' => 'UA',
            'Белоруссия' => 'BY'
        ];

        if (!empty($advertisement->advertisement_country) && App::getLocale() == 'ru') {
            $result->name = $advertisement->advertisement_country;

            if (!empty($advertisement->advertisement_city_ext_code)) {
                $result->code = $advertisement->advertisement_country_ext_code;
                return $result;
            }

            if (!empty($countryCodes[$advertisement->advertisement_country])) {
                $result->code = $countryCodes[$advertisement->advertisement_country];
                return $result;
            }

            return $result;
        }


        if (!empty($advertisement->advertisement_country_ext_code)) {
            try {
                $country = $this->GeoDBHelper->getCountryName($advertisement->advertisement_country_ext_code, App::getLocale());

                if (!empty($country)) {
                    $result->name = $country;
                    $result->code = $advertisement->advertisement_country_ext_code;
                    return $result;
                }

            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        if (!empty($advertisement->company_id)) {

            if (!empty($advertisement->company_country) && App::getLocale() == 'ru') {
                $result->name= $advertisement->company_country;

                if (!empty($advertisement->company_country_id)) {
                    $result->code = $advertisement->company_country_id;
                    return $result;
                }

                if (!empty($countryCodes[$advertisement->company_country])) {
                    $result->code = $countryCodes[$advertisement->company_country];
                    return $result;
                }

                return $result;
            }


            if (!empty($advertisement->company_country_id)) {
                try {
                    $country = $this->GeoDBHelper->getCountryName($advertisement->company_country_id, App::getLocale());

                    if (!empty($country)) {
                        $result->name = $country;
                        $result->code = $advertisement->company_country_id;
                        return $result;
                    }
                } catch (Exception $exception) {
                    Log::critical("GEODB Error", $exception->getTrace());
                }
            }

        }

        if (!empty($advertisement->user_country) && App::getLocale() == 'ru') {
            $result->name = $advertisement->user_country;

            if (!empty($advertisement->user_country_id)) {
                $result->code = $advertisement->user_country_id;
                return $result;
            }

            if (!empty($countryCodes[$advertisement->user_country])) {
                $result->code = $countryCodes[$advertisement->user_country];
                return $result;
            }

            return $result;
        }


        if (!empty($advertisement->user_country_id)) {
            try {
                $country = $this->GeoDBHelper->getCountryName($advertisement->user_country_id, App::getLocale());

                if (!empty($country)) {
                    $result['name'] = $country;
                    $result['code'] = $advertisement->user_country_id;
                    return $result;
                }
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        return $result;
    }


    /**
     * @param $author
     * @param null $categoryKey
     * @param null $advertisementType
     * @return array
     */
    private function getAdvertisementsAndCategories($author, $categoryKey = null, $advertisementType = null): array
    {
        $date = $this->commonHelper->getInvertMonth();
        $advertisements = $this->advertisementRepository->getAuthorAllAdvertisements($author, $date, $categoryKey, $advertisementType);

        $ids = $advertisements->pluck('category_id')->unique()->values()->all();
        $categories = $this->categoryHelper->getCategoriesByIds($ids);

        $result['advertisement_list'] = $advertisements;
        $result['categories'] = $categories->unique('category_key')->values();

        return $result;
    }

    /**
     * @param $advertisements
     * @return array
     */
    private function getCollects($advertisements)
    {
        // получаем все категории для перевода названий
        $categoryPerformer = collect([]);;
        if (!empty($advertisements
            ->where('category_type', CategoryTypeEnum::PERFORMER)
            ->first()))
            $categoryPerformer = $this->categoryHelper->getCategoryList(CategoryTypeEnum::PERFORMER);

        $categoryEmployer = collect([]);
        if (!empty($advertisements
            ->where('category_type', CategoryTypeEnum::EMPLOYER)
            ->first()))
            $categoryEmployer = $this->categoryHelper->getCategoryList(CategoryTypeEnum::EMPLOYER);

        $currencyCodes = $advertisements->pluck('currency_code')->unique()->values()->all();
        $currencies = $this->languageHelper->getTranslations($currencyCodes, App::getLocale());

        return [
            $categoryPerformer,
            $categoryEmployer,
            $currencies,
        ];
    }

    /**
     * @param $advertisement
     * @param $contactShow
     * @return array
     */
    private function getPerson($advertisement, $contactShow)
    {
        $personContacts = $this->getPersonContacts($advertisement, $contactShow);
        $personSocial = $this->getPersonSocial($advertisement, $contactShow);

        $advertisementUser = $advertisement->user;
        $advertisementCompany = null;
        $phone = !empty($advertisementUser->phone) ? $advertisementUser->phone : '';
        $person = [
            'id' => $advertisementUser->id,
            'name' => $advertisementUser->name,
            'avatar' => (!empty($advertisementUser->avatar) && $advertisementUser->avatar != 'users/default.png') ?
                asset(Storage::url($advertisementUser->avatar)) : url('storage/default/user.png'),
            'phone' => $contactShow ? $phone : Str::limit($phone, 2, 'XXXXXXX'),
            'contacts' => $personContacts,
            'social' => $personSocial,
            'created_at' => $advertisementUser->created_at->format('d.m.Y'),
            'company_is_verification' => false,
        ];

        if (!empty($advertisement->company_id)) {

            $advertisementCompany = $advertisement->company;
            $phone = !empty($advertisementCompany->phone) ? $advertisementCompany->phone : '';
            $person = [
                'id' => $advertisementCompany->id,
                'name' => $advertisementCompany->name,
                'avatar' => $advertisementCompany->logo_id ?
                    asset(Storage::url(Image::find($advertisementCompany->logo_id)->photo_url)) : url('storage/default/company.png'),
                'phone' => $contactShow ? $phone : Str::limit($phone, 2, 'XXXXXXX'),
                'contacts' => $personContacts,
                'created_at' => $advertisementCompany->created_at->format('d.m.Y'),
                'company_is_verification' => $advertisementCompany->is_verification,
            ];
        }

        return [$person, $advertisementUser, $advertisementCompany];

    }

    /**
     * @param $advertisement
     * @param $countryCode
     * @param $country
     * @return bool
     */
    private function checkCountry($advertisement, $countryCode, $country)
    {

        if (!empty($advertisement->company_id) &&
            (!empty($advertisement->company_country) || !empty($advertisement->company_country_id))) {

            if (Str::lower($countryCode) == Str::lower($advertisement->company_country_id))
                return true;

            if (!empty($advertisement->company_country) && App::getLocale() == 'ru' && $advertisement->company_country == $country)
                return true;

            return false;
        }

        if (empty($advertisement->user_country) && empty($advertisement->user_country_id))
            return false;

        if (Str::lower($countryCode) == Str::lower($advertisement->user_country_id))
            return true;

        if (!empty($advertisement->user_country) && App::getLocale() == 'ru' && $advertisement->user_country == $country)
            return true;

        return false;
    }

    /**
     * @param $advertisement
     * @param $cityCode
     * @param $city
     * @return bool
     */
    private function checkCity($advertisement, $cityCode, $city)
    {

        if (!empty($advertisement->company_id) &&
            (!empty($advertisement->company_city) || !empty($advertisement->company_city_id))) {

            if (Str::lower($cityCode) == Str::lower($advertisement->company_city_id))
                return true;

            if (!empty($$advertisement->company_city) && App::getLocale() == 'ru' && $advertisement->company_city == $city)
                return true;

            return false;
        }

        if (empty($advertisement->user_city) && empty($advertisement->user_city_id))
            return false;

        if (Str::lower($cityCode) == Str::lower($advertisement->user_city_id))
            return true;

        if (!empty($advertisement->user_city) && App::getLocale() == 'ru' && $advertisement->user_city == $city)
            return true;

        return false;
    }

    /**
     * @param $advertisement
     * @param $categoryPerformer
     * @param $categoryEmployer
     * @param $currencies
     * @param $advertisementFavoriteIds
     * @return array
     */
    private function getSmallCardFormatForAdvertisement($advertisement, $categoryPerformer, $categoryEmployer, $currencies, $advertisementFavoriteIds)
    {
        $item = [];
        $item['advertisement_id'] = $advertisement->advertisement_id;
        $item['advertisement_type_key'] = $advertisement->advertisement_type;
        $item['title'] = $advertisement->title;

        $city = $this->getCity($advertisement);
        $item['city'] =  $city['city'];
        $item['latitude'] = $city['latitude'];
        $item['longitude'] = $city['longitude'];

        $item['image'] = $advertisement->photo_id ?
            asset(Storage::url(Image::find($advertisement->photo_id)->photo_url)) :
            url('storage/default/product.png');

        $dateTime = new DateTime('NOW');
        $item['is_allocate'] = new DateTime($advertisement->is_allocate_at) > $dateTime;
        $item['is_top_country'] = new DateTime($advertisement->is_top_country_at) > $dateTime;
        $item['is_urgent'] = new DateTime($advertisement->is_urgent_at) > $dateTime;

        $formFields = $this->categoryHelper->getCategoryFormFields($advertisement->category_id, $advertisement->child_category_id, true);

        foreach ($formFields as $k => $formField) {

            if ($k == 'price_group')
                continue;

            if (!$formField->is_show)
                continue;

            $key = $formField->key;

            $item[$key] = $advertisement->$key;

            if ($formField->type == 'checkbox')
                $item[$key] = $advertisement->$key == 2 ? true : false;

            if ($key == 'sample')
                $item['sample'] = empty($advertisement->sample) ? '' : url('storage/' . $advertisement->sample);

            $item[$key . '_name'] = $formField->name;

            if (in_array($key, ['length', 'width'])) {
                $item[$key . '_hint'] = $formField->hint;

                if ($formField->hint == 'мм')
                    $item[$key] = 10 * $item[$key];

            }
        }

        $paymentName = AdvertisementPaymentEnum::NONE;
        if (!empty(AdvertisementPaymentEnum::ALL[(int)$advertisement->payment]) && (int)$advertisement->payment > 0)
            $paymentName = __('advertisement.' . AdvertisementPaymentEnum::ALL[(int)$advertisement->payment]);

        $item['payment'] = $paymentName;

        $item['price_type'] = __('advertisement.' . PriceTypeEnum::ALL[$advertisement->price_type]);

        $item['price'] = $advertisement->price;
        $item['price_name'] = $formFields['price_group']['price']->name;


        // category
        $item['category_name'] = '';
        $advertisement->category = null;
        if ($advertisement->category_type == CategoryTypeEnum::PERFORMER) {

            $advertisement->category = $categoryPerformer
                ->where('category_id', $advertisement->category_id)
                ->first();
        }

        if ($advertisement->category_type == CategoryTypeEnum::EMPLOYER) {

            $advertisement->category = $categoryEmployer
                ->where('category_id', $advertisement->category_id)
                ->first();
        }
        $item['category_name'] = $advertisement->category->category_name;

        $advertisement->child_category = null;
        if (!empty($advertisement->child_category_id)) {
            $advertisement->child_category = $this->categoryHelper->getChildCategory($advertisement->child_category_id);
            $item['category_name'] = $advertisement->child_category->child_category_name;
        }

        $item['translation_currency_code'] = $currencies[$advertisement->currency_code];

        $item['person'] = $advertisement->user_name;
        $item['company_is_verification'] = false;
        if (!empty($advertisement->company_id)) {
            $item['person'] = $advertisement->company_name;
            $item['company_is_verification'] = $advertisement->company_is_verification;
        }


        $item['is_favorite'] = false;
        if (in_array($advertisement->advertisement_id, $advertisementFavoriteIds))
            $item['is_favorite'] = true;

        return $item;
    }


}
