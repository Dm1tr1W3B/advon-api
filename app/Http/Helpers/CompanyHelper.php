<?php

namespace App\Http\Helpers;


use App\Models\Advertisement;
use App\Models\Company;
use App\Models\CompanyPhone;
use App\Models\Image;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Carbon\Carbon;
use DateTime;
use Error;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyHelper
{
    /**
     * @var int
     */
    private static $maxCountOfAdditionalImages = 3;
    private $numberOfCompanies = 18;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;

    public function __construct(GeoDBHelper $GeoDBHelper,
                                CompanyRepository $companyRepository,
                                CommonHelper $commonHelper,
                                AuthHelper $authHelper)
    {
        if (!$GeoDBHelper)
            $GeoDBHelper = new GeoDBHelper();
        $this->GeoDBHelper = $GeoDBHelper;

        $this->companyRepository = $companyRepository;
        $this->commonHelper = $commonHelper;
        $this->authHelper = $authHelper;
    }

    /**
     * @param Company $company
     * @param UploadedFile $newAvatar
     * @return void
     * @throws Exception
     */
    public function changeAvatar($company, UploadedFile $newAvatar)
    {
        try {
            $photo_id = ImageHelper::createPhotoFromRequest('company_main_photo', $newAvatar)->id;
            if (!$photo_id)
                throw  new Error("Error to upload new main photo");

            if (!$company)
                Cache::put('save_photo_for_company_' . auth()->user()->id, $photo_id);
            else {
                $this->deleteMainPhoto($company);
                $company->photo_id = $photo_id;
                $company->save();
            }
        } catch (\Exception $exception) {
            throw  new Exception($exception->getMessage());
        }
    }

    /**
     * @param $request
     * @return array
     * @throws Exception
     */
    public function generateGeoDbData($request)
    {
        if ($request->has('longitude') && $request->has('latitude')) {
            try {
                return $this->GeoDBHelper->generateGeoDbDataByCoordinates($request->get('longitude'), $request->get('latitude'));
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
        return [];
    }


    /**
     * @param User $user
     * @param $fields
     * @return array
     */
    public function replaceNullableAttributesWithUsers($user, $fields)
    {
        $company = new Company();
        $commonFields = collect($user->getFillable())->intersect($company->getFillable());

        foreach ($commonFields as $commonField) {
            if (!isset($fields[$commonField]))
                $fields[$commonField] = $user->$commonField;
        }
        return $fields;
    }

    /**
     * @param Company $company
     * @param array $additionalPhotos
     * @throws Exception
     */
    public function uploadAdditionalPhotos($company, array $additionalPhotos)
    {
        $photos_id = [];
        if (!$company) {
            foreach ($additionalPhotos as $additionalPhoto) {
                if (count($photos_id) <= self::$maxCountOfAdditionalImages) {
                    $photos_id[] = ImageHelper::createPhotoFromRequest('company_additional_photos', $additionalPhoto)->id;
                } else {
                    throw  new Exception("Exceeded the number of images.Maximum number is :" . self::$maxCountOfAdditionalImages);
                }
            }
            Cache::put('save_photos_for_company_' . auth()->user()->id, $photos_id);

        } else {
            foreach ($additionalPhotos as $additionalPhoto) {
                if ($company->images()->count() < self::$maxCountOfAdditionalImages) {
                    $photos_id[] = ImageHelper::createPhotoFromRequest('company_additional_photos', $additionalPhoto)->id;
                } else {
                    throw  new Exception("Exceeded the number of images.Maximum number is :" . self::$maxCountOfAdditionalImages);
                }
            }
            $company->images()->attach($photos_id);
        }
    }

    /**
     * @param $company
     * @param array $additionalPhotos
     * @return mixed
     * @throws Exception
     */
    public function uploadProfileAdditionalPhotos_NoCache($company, array $additionalPhotos)
    {
        $photos_id = [];
        foreach ($additionalPhotos as $additionalPhoto) {
            $photos_id[] = ImageHelper::createPhotoFromRequest('companies/company_additional_photos', $additionalPhoto)->id;
        }
        $company->images()->attach($photos_id);

        return $photos_id[0];
    }

    /**
     * @param Company $company
     * @throws Exception
     */
    public function deleteMainPhoto($company)
    {
        try {
            $id = $company->photo_id;
            $company->photo_id = null;
            $company->save();
            if (isset($id))
                Image::destroy($id);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    public function checkCompanyBelongsToUser($user, $company_id)
    {
        if (!$user || !$user->company)
            return false;
        return $user->company->id == $company_id;
    }

    /**
     * @param $hashtags array
     * @return string
     */
    public function makeHashtags($hashtags)
    {
        return implode(',', $hashtags);
    }

    /**
     * @param $hashtags string
     * @return string
     */
    public function getHashtags($hashtags)
    {
        return explode(',', $hashtags);
    }

    /**
     * @param $user
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws Exception
     */
    public function getCompany($user)
    {
        $company = $this->companyRepository->getCompany($user->id);

        $company->site_url = !empty($company->site_url) ? $company->site_url : '';
        $company->video_url = !empty($company->video_url) ? $company->video_url : '';
        $company->description = (!empty($company->description) && $company->description !='null') ?
            $company->description : '';

        $hashtags = [];
        if (!empty($company->hashtags)) {
            $hashtags = explode(',', $company->hashtags);
            $hashtags = array_map('trim', $hashtags);
        }

        $company->hashtags = $hashtags;
        $company->site_url = !empty($company->site_url) ? $company->site_url : '';

        $audio = '';
        if (!empty($company->audio_url)) {
            $audio = json_decode($company->audio_url, true) [0];
            $audio['download_link'] = url('storage' .  $audio['download_link']);
        }
        $company->audio = $audio;

        $document = '';
        if (!empty($company->document_url)) {
            $document = json_decode($company->document_url, true) [0];
            $document['download_link'] = url('storage' .  $document['download_link']);
        }
        $company->document = $document;

        $company->logo = !empty($company->photo_url) ? url('storage/' . $company->photo_url) : url('storage/default/company.png');
        $company->phone = !empty($company->phone) ? $company->phone : '';
        $company->email = !empty($company->email) ? $company->email : '';

        $createdAt = new DateTime($company->created_at);
        $company->created_at = $createdAt->format('d.m.Y');

        $company->country = !empty($company->country) ? $company->country : '';
        $company->region = !empty($company->region) ? $company->region : '';
        $company->city = !empty($company->city) ? $company->city : '';

        if (app()->getLocale() != 'ru' &&
            !empty($company->longitude) &&
            !empty($company->latitude)
        ) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($company->latitude, $company->longitude, 10, app()->getLocale())->first();

                if (!empty($data)) {
                    $company->country = $data->country;
                    $company->region = $data->region;
                    $company->city = $data->city;
                }

            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        $company->additional_photos = $this->getAdditionalPhotos($company->id);

        $company->additional_phones = [];
        $phones = CompanyPhone::where('company_id', $company->id)->get();
        if ($phones->isNotEmpty())
            $company->additional_phones = $phones->pluck('phone')->all();


        $company->contacts = $this->getCompanyContacts($company->id, true);

        $company->social_media = $this->companyRepository->getCompanySocialMedias($company->id)
            ->map(function ($socialMedia) {
                $socialMedia->photo_url = url('storage/' . $socialMedia->photo_url);
                return $socialMedia;
            });

        unset($company->audio_url, $company->document_url, $company->photo_url);

        return $company;
    }

    /**
     * @param $countryCode
     * @param $cityCode
     * @return array
     */
    public function getCompanyLogoList($countryCode = null, $cityCode  = null)
    {
        $companyLogoList = [];
        $i = 0;
        list($country, $city) = $this->GeoDBHelper->getCountryAmdCityName($countryCode, $cityCode);

        $this->companyRepository->getCompanyLogoList($countryCode, $cityCode, $country, $city)->each(function ($company) use ($countryCode, $cityCode, $country, $city, &$i, &$companyLogoList) {

            if ($i >= $this->numberOfCompanies)
                return false;

            if (!empty($countryCode) && !$this->checkCountry($company, $countryCode, $country))
                return true;

            if (!empty($cityCode) && !$this->checkCity($company, $cityCode, $city))
                return true;

            $company->company_logo = !empty($company->photo_url) ? url('storage/' . $company->photo_url) : url('storage/default/company.png');

            unset(
                $company->photo_url,
                $company->latitude,
                $company->longitude,
                $company->country,
                $company->city,
                $company->country_id,
                $company->city_id,
            );

            $companyLogoList[] = $company;
            $i++;
        });

        return $companyLogoList;
    }

    /**
     * @param $companyId
     * @param $contactShow
     * @return mixed
     */
    public function getCompanyContacts($companyId, $contactShow)
    {
        return $this->companyRepository->getCompanyContacts($companyId)
            ->map(function ($contact)  use ($contactShow) {
                $contact->photo_url = url('storage/' . $contact->photo_url);

                if (!$contactShow)
                    $contact->values = Str::limit($contact->values, 2, 'XXXXXXX');

                return $contact;
            });
    }

    /**
     * @param $companyId
     * @param $contactShow
     * @return mixed
     */
    public function getCompanySocial($companyId, $contactShow)
    {
        return $this->companyRepository->getCompanySocial($companyId)
            ->map(function ($social)  use ($contactShow) {
                $social->photo_url = url('storage/' . $social->photo_url);

                $smt = [
                    'Facebook' => 'https://www.facebook.com/',
                    'Instagram' => 'https://www.instagram.com/',
                    'VK' => 'https://vk.com/',
                    'OK' => 'https://ok.ru/',
                ];

                if (!$contactShow)
                    $social->values = ''; // Str::limit($social->values, 2, 'XXXXXXX');
                else {
                    $social->values = !empty($smt[$social->name]) ? $smt[$social->name] . $social->values : '';
                }

                return $social;
            });
    }

    /**
     * @param string $advertisementType
     * @param null $search
     * @param null $countryCode
     * @param null $cityCode
     * @return array
     */
    public function getCompanyListForGuest(string $advertisementType, $search = null, $countryCode = null, $cityCode = null)
    {
        list($country, $city) = $this->GeoDBHelper->getCountryAmdCityName($countryCode, $cityCode);

        $query = [
            'country_code' => $countryCode,
            'country_name' => $country,
            'city_code' => $cityCode,
            'city_name' => $city,
            'advertisement_type' => $advertisementType,
        ];
        return $this->companyRepository->getCompanyListForGuest($query, $search);
    }


    /**
     * @param $companies
     * @return mixed
     * @throws Exception
     */
    public function getCompanyListForGuestFormat($companies)
    {
        if ($companies->isEmpty())
            return $companies;

        return $companies->map(function ($company) {


            $company->company_logo = !empty($company->photo_url) ? url('storage/' . $company->photo_url) : url('storage/default/company.png');

            $company->city = $this->getCompanyCity($company);

            $company->company_contacts = $this->getCompanyContacts($company->company_id, true);
            $company->company_phone = empty($company->company_phone) ? '' : (int) $company->company_phone;
            $company->latitude = empty($company->latitude) ? 0 :  $company->latitude;
            $company->longitude = empty($company->longitude) ? 0 :  $company->longitude;

            $date = $this->commonHelper->getInvertMonth();
            $company->advertisement_count = Advertisement::where('company_id', $company->company_id)
                ->where('is_published', true)
                ->where('published_at', '>=', $date)
                ->where('is_hide', false)
                ->count();

            $dateNow = new DateTime("now");

            $company->is_top = false;
            if (!empty($company->is_top_at)) {
                $topAt = new DateTime($company->is_top_at);

                if ($topAt > $dateNow)
                    $company->is_top = true;
            }


            $company->is_allocate = false;
            if (!empty($company->is_allocate_at))  {
                $allocateAt = new DateTime($company->is_allocate_at);

                if ($allocateAt > $dateNow)
                    $company->is_allocate = true;
            }

            unset(
                $company->photo_url,
                $company->country,
                $company->country_id,
                $company->city_id,
                $company->is_top_at,
                $company->is_allocate_at,
            );

            return $company;

        });
    }


    /**
     * @param string $advertisementType
     * @param null $search
     * @param null $countryCode
     * @param null $cityCode
     * @return array
     */ /*
    public function getCompanyListForGuest(string $advertisementType, $search = null, $countryCode = null, $cityCode = null)
    {
        $companyList = [];

        $advertisementCount = $this->companyRepository->getAdvertisementCount($advertisementType);

        if ($advertisementCount->isEmpty())
            return $companyList;

        $ids = $advertisementCount->pluck('company_id')->all();

        $companies = $this->companyRepository->getCompanyListForGuest($ids, $search);

        if ($companies->isEmpty())
            return $companyList;

        list($country, $city) = $this->GeoDBHelper->getCountryAmdCityName($countryCode, $cityCode);


        $companies->each(function ($company) use ($countryCode, $cityCode, $country, $city, $advertisementCount, &$companyList) {


            $company->company_logo = !empty($company->photo_url) ? url('storage/' . $company->photo_url) : url('storage/default/company.png');

            if (!empty($countryCode) && !$this->checkCountry($company, $countryCode, $country))
                return true;

            if (!empty($cityCode) && !$this->checkCity($company, $cityCode, $city))
                return true;

            $company->city = $this->getCompanyCity($company);

            $company->company_contacts = $this->getCompanyContacts($company->company_id, true);
            $company->company_phone = empty($company->company_phone) ? '' : (int) $company->company_phone;
            $company->latitude = empty($company->latitude) ? 0 :  $company->latitude;
            $company->longitude = empty($company->longitude) ? 0 :  $company->longitude;

            $company->advertisement_count = $advertisementCount
                ->where('company_id', $company->company_id)
                ->first()
                ->advertisement_count;


            unset(
                $company->photo_url,
                $company->country,
                $company->country_id,
                $company->city_id,
            );

            $companyList[] = $company;

        });

        return $companyList;

    }
    */

    /**
     * @param $company
     * @return array
     */
    public function getCompanyForGuest($company)
    {
        $companyForGuest = [];

        $hashtags = [];
        if (!empty($company->hashtags)) {
            $hashtags = explode(',', $company->hashtags);
            $hashtags = array_map('trim', $hashtags);
        }
        $companyForGuest['main'] = [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'company_description' => $company->description,
            'additional_photos' => $this->getAdditionalPhotos($company->id),
            'hashtags' => $hashtags,
            'city' => $this->getCompanyCity($company),
            'created_at' => $company->created_at->format('d.m.Y'),
            'company_is_verification' => $company->is_verification,
        ];

        $contactShow = false;

        $phone = '';
        if (!empty($company->phone ))
            $phone = $contactShow ? (int) $company->phone : Str::limit((int) $company->phone, 2, 'XXXXXXX');

        $date = $this->commonHelper->getInvertMonth();
        $companyForGuest['person'] = [
            'id' => $company->id,
            'name' => $company->name,
            'avatar' => $company->logo_id ? asset(Storage::url(Image::find($company->logo_id)->photo_url)) : url('storage/default/company.png'),
            'phone' => $phone,
            'contacts' => $this->getCompanyContacts($company->id, $contactShow),
            'social' => $this->getCompanySocial($company->id, $contactShow),
            'created_at' => $company->created_at->format('d.m.Y'),
            'number_advertisement' => Advertisement::where('company_id', $company->id)
                ->where('advertisements.is_published', true)
                ->where('advertisements.published_at', '>=', $date)
                ->where('is_hide', false)
                ->count(),
        ];

        return $companyForGuest;
    }

    /**
     * @param $companyId
     * @return array
     */
    private function getAdditionalPhotos($companyId)
    {
        $additionalPhotos = [];
        $this->companyRepository->getAdditionalPhotos($companyId)
            ->each(function ($image) use (&$additionalPhotos) {;
                $additionalPhoto = [];
                $additionalPhoto['image_id'] = $image->image_id;
                $additionalPhoto['photo_url'] = url('storage/' . $image->photo_url);

                $additionalPhotos[] = $additionalPhoto;
            });
        return $additionalPhotos;
    }

    /**
     * @param $company
     * @return string
     */
    public function getCompanyCity($company): string
    {
        $city = '';

        if (!empty($company->city) && App::getLocale() == 'ru')
            return $company->city;

        if (!empty($company->latitude) && !empty($company->longitude)) {

            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($company->latitude,
                    $company->longitude,
                    10,
                    App::getLocale())->first();

                if (!empty($data->city))
                    return $data->city;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
                return '';
            }
        }

        return $city;
    }

    /**
     * @param $company
     * @param $countryCode
     * @param $country
     * @return bool
     */
    private function checkCountry($company, $countryCode, $country)
    {
        if (Str::lower($countryCode) == Str::lower($company->country_id))
            return true;

        if (!empty($company->country) && App::getLocale() == 'ru' && $company->country == $country)
            return true;

        return false;
    }

    /**
     * @param $company
     * @param $cityCode
     * @param $city
     * @return bool
     */
    private function checkCity($company, $cityCode, $city)
    {
        if (Str::lower($cityCode) == Str::lower($company->city_id))
            return true;

        if (!empty($company->city) && App::getLocale() == 'ru' && $company->city == $city)
            return true;

        /*
        if (!empty($company->latitude) && !empty($company->longitude)) {

            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($company->latitude,
                    $company->longitude,
                    100,
                    App::getLocale())->first();

                if (!empty($data->city) && $data->city == $city)
                    return true;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
                return false;
            }
        }
        */

        return false;
    }




}
