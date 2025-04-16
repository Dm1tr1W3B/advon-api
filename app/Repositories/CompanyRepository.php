<?php

namespace App\Repositories;

use App\Models\Advertisement;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\CompanySocial;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CompanyRepository
{
    public function getCompanyLogoList($countryCode = null, $cityCode = null, $country = null, $city = null)
    {
        $companies = Company::leftjoin('images as i', 'i.id', '=', 'companies.logo_id')
            ->select(
                'companies.id as company_id',
                'companies.name as company_name',
                'i.photo_url',
                'companies.latitude',
                'companies.longitude',
                'companies.country',
                'companies.city',
                'companies.country_id',
                'companies.city_id'
            );

        $companies->join('user_settings as us', 'us.user_id', '=', 'companies.owner_id')
            ->where('us.is_hide_company', false);


        if (!empty($cityCode)) {
            $companies = $companies->where(function ($query) use ($cityCode, $city) {
                $query->where('companies.city_id', $cityCode);

                if (!empty($city) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(companies.city)'), Str::lower($city));
                }
            });
        }

        if (!empty($countryCode)) {
            $company = $companies->where(function ($query) use ($countryCode, $country) {
                $query->where('companies.country_id', $countryCode);

                if (!empty($country) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(companies.country)'), Str::lower($country));
                }
            });
        }

        return $companies->orderBy('companies.created_at', 'DESC')
            ->get();
    }

    /**
     * @param int $userId
     * @return Model|null
     */
    public function getCompany(int $userId): ?Model
    {
        return Company::leftjoin('images as i', 'i.id', '=', 'companies.logo_id')
            ->select(
                'companies.id as id',
                'companies.name as name',
                'companies.description as description',
                'companies.site_url as site_url',
                'companies.hashtags as hashtags',
                'companies.video_url as video_url',
                'companies.audio_url as audio_url',
                'companies.document_url as document_url',

                'i.photo_url',

                'companies.latitude',
                'companies.longitude',
                'companies.country',
                'companies.region',
                'companies.city',

                'companies.phone as phone',
                'companies.email as email',
                'companies.created_at as created_at',
                'companies.is_verification as company_is_verification'
            )
            ->where('owner_id', $userId)
            ->first();
    }

    /**
     * @param int $companyId
     * @return Collection
     */
    public function getAdditionalPhotos(int $companyId)
    {
        return Image::join('company_images as ci', 'ci.image_id', '=', 'images.id')
            ->select('images.id as image_id', 'images.photo_url')
            ->where('ci.company_id', $companyId)
            ->get();
    }

    /**
     * @param int $companyId
     * @return Collection
     */
    public function getCompanyContacts(int $companyId)
    {
        return CompanyContact::leftjoin('contact_types as ct', 'ct.id', '=', 'company_contact.contact_id')
            ->leftjoin('images as i', 'i.id', '=', 'ct.image_id')
            ->select('i.photo_url', 'ct.name', 'company_contact.values')
            ->where('company_contact.company_id', $companyId)
            ->get();
    }

    /**
     * @param int $companyId
     * @return mixed
     */
    public function getCompanySocial(int $companyId)
    {
        return CompanySocial::leftjoin('social_media_types as smt', 'smt.id', '=', 'company_social.social_id')
            ->leftjoin('images as i', 'i.id', '=', 'smt.image_id')
            ->select('i.photo_url', 'smt.name', 'company_social.values')
            ->where('company_social.company_id', $companyId)
            ->get();
    }

    /**
     * @param int $companyId
     * @return Collection
     */
    public function getCompanySocialMedias(int $companyId)
    {
        return CompanySocial::leftjoin('social_media_types as smt', 'smt.id', '=', 'company_social.social_id')
            ->leftjoin('images as i', 'i.id', '=', 'smt.image_id')
            ->select('i.photo_url', 'smt.name', 'company_social.values')
            ->where('company_social.company_id', $companyId)
            ->get();
    }

    /**
     * @param $advertisementType
     * @return Collection
     */
    public function getAdvertisementCount($advertisementType)
    {
        return Advertisement::select('company_id', DB::raw('count(*) as advertisement_count'))
            ->where('type', $advertisementType)
            ->whereNotNull('company_id')
            ->groupBy('company_id')
            ->get();
    }


    public function getCompanyListForGuest(array $filter, $search = null)
    {
        $companies = Company::leftjoin('images as i', 'i.id', '=', 'companies.logo_id')
            ->select(
                'companies.id as company_id',
                'companies.name as company_name',
                'companies.phone as company_phone',
                'i.photo_url',
                'companies.latitude',
                'companies.longitude',
                'companies.country',
                'companies.city',
                'companies.country_id',
                'companies.city_id',
                'companies.is_top_at',
                'companies.is_allocate_at',
                'companies.is_verification as company_is_verification'
            )
            ->whereExists(function ($query) use ($filter) {
                $query->select(DB::raw(1))
                    ->from('advertisements')
                    ->where('advertisements.type', $filter['advertisement_type'])
                    ->whereColumn('advertisements.company_id', 'companies.id');
            })

        ;

        $companies->join('user_settings as us', 'us.user_id', '=', 'companies.owner_id')
            ->where('us.is_hide_company', false);

        if (!empty($filter['city_code'])) {
            $companies = $companies->where(function ($query) use ($filter) {
                $query->where('companies.city_id', $filter['city_code']);

                if (!empty($filter['city_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(companies.city)'), Str::lower($filter['city_name']));
                }
            });
        }

        if (!empty($filter['country_code'])) {
            $companies = $companies->where(function ($query) use ($filter) {
                $query->where('companies.country_id', $filter['country_code']);

                if (!empty($filter['country_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(companies.country)'), Str::lower($filter['country_name']));
                }
            });
        }

        if (!empty($search))
            $companies = $companies->where(function ($query) use ($search) {
                $query->where(DB::raw('lower(companies.name)'), 'like', '%' . Str::lower($search) . '%')
                    ->orWhere(DB::raw('lower(companies.phone)'), 'like', '%' . Str::lower($search) . '%');
            });

        return $companies->orderBy('companies.is_top_at', 'DESC')
            ->get();
    }

}
