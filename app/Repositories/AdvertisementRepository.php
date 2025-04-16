<?php

namespace App\Repositories;

use App\Http\Enums\PersonTypeEnum;
use App\Http\Enums\SearchTypeEnum;
use App\Models\Advertisement;
use App\Models\AdvertisementComment;
use App\Models\AdvertisementFavorite;
use App\Models\AdvertisementView;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdvertisementRepository
{


    /**
     * @param int $userId
     * @param string $advertisementType
     * @param $date
     * @param null $search
     * @return Collection
     */
    public function getAdvertisementFavoriteList(int $userId, string $advertisementType, $date, $search = null): Collection
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->join('advertisement_favorites', 'advertisement_favorites.advertisement_id', '=', 'advertisements.id')
            ->where('advertisement_favorites.user_id', $userId)
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.type', $advertisementType);

        $advertisements = $this->addUserSettings($advertisements);


        if (!empty($search))
            $advertisements = $advertisements->where(function ($query) use ($search) {
                $query->whereJsonContains('advertisements.hashtags', $search)
                    ->orwhere(DB::raw('lower(advertisements.description)'), 'like', '%' . Str::lower($search) . '%')
                    ->orWhere(DB::raw('lower(advertisements.title)'), 'like', '%' . Str::lower($search) . '%');
            });

        return $advertisements->get();
    }


    /**
     * @param array $advertisementIds
     * @param int $userId
     */
    public function deleteAdvertisementsFavorite(array $advertisementIds, int $userId)
    {
        DB::transaction(function () use ($advertisementIds, $userId) {
            AdvertisementFavorite::whereIn('advertisement_id', $advertisementIds)
                ->where('user_id', $userId)
                ->delete();
        });
    }


    /**
     * @param array $ids
     * @param null $userId
     * @return Collection
     */
    public function getAdvertisementsByIds(array $ids, $userId = null): Collection
    {
        $advertisements = Advertisement::whereIn('id', $ids);

        if (!empty($userId))
            $advertisements = $advertisements->where('user_id', $userId);

        return $advertisements->get();
    }

    /**
     * @param string $personType
     * @param int $userId
     * @param null $search
     * @param null $categoryId
     * @return Collection
     */
    public function advertisementListForUser(string $personType, int $userId, $search = null, $categoryKey = null): Collection
    {
        $advertisements = Advertisement::join('currencies as c', 'c.id', '=', 'advertisements.currency_id')
            ->join('categories', 'categories.id', '=', 'advertisements.category_id')
            ->select(
                'advertisements.id as advertisement_id',
                'advertisements.title',
                'advertisements.category_id  as category_id',
                'advertisements.child_category_id',
                'advertisements.price',
                'advertisements.price_type',
                'advertisements.payment',
                'advertisements.is_published',
                'advertisements.published_at',
                'advertisements.views_total as number_views',
                'advertisements.views_contact as number_contacts',
                'advertisements.photo_id',
                'c.code as currency_code',
                'categories.type as category_type',
                'advertisements.is_hide',
                'advertisements.is_allocate_at',
                'advertisements.is_top_country_at',
                'advertisements.is_urgent_at'
            )
            ->where('advertisements.user_id', $userId);

        if ($personType == PersonTypeEnum::COMPANY)
            $advertisements = $advertisements->whereNotNull('company_id');

        if ($personType == PersonTypeEnum::PRIVATE_PERSON)
            $advertisements = $advertisements->whereNull('company_id');

        if (!empty($categoryKey))
            $advertisements = $advertisements->where('categories.key', $categoryKey);

        if (!empty($search))
            $advertisements = $advertisements->where(function ($query) use ($search) {
                $query->whereJsonContains('advertisements.hashtags', $search)
                    ->orwhere(DB::raw('lower(advertisements.description)'), 'like', '%' . Str::lower($search) . '%')
                    ->orWhere(DB::raw('lower(advertisements.title)'), 'like', '%' . Str::lower($search) . '%');
            });

        return $advertisements->get();
    }

    /**
     * @param int $advertisementId
     * @param null $date
     * @return mixed
     */
    public function getNumberViews(int $advertisementId, $date = null)
    {
        $advertisementView = AdvertisementView::where('advertisement_id', $advertisementId);

        if (!empty($date))
            $advertisementView = $advertisementView->where('created_at', '>', $date);

        return $advertisementView->count();
    }

    /**
     * @param $user
     * @param $ip
     * @param $limit
     * @param $date
     * @return mixed
     */
    public function getLastAdvertisement($user, $ip, $limit, $date)
    {
        $advertisementView = $this->getAdvertisementQueryBuilder()
            ->join('advertisement_views as aw', 'aw.advertisement_id', '=', 'advertisements.id')
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false)
            ->orderBy('aw.created_at', 'DESC');

        $advertisementView = $this->addUserSettings($advertisementView);

        if (!empty($user))
            $advertisementView = $advertisementView->where('aw.user_id', $user->id);
        else
            $advertisementView = $advertisementView->where('aw.ip', $ip);

        return $advertisementView->get()
            ->unique('advertisement_id')
            ->take($limit)
            ->values();
    }

    /**
     * @param $advertisement
     * @param $date
     * @return mixed
     */
    public function getIntersectAdvertisements($advertisement, $date)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('categories.id', $advertisement->category_id)
            ->where('advertisements.id', '!=', $advertisement->id)
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        $advertisements = $this->addUserSettings($advertisements);

        return $advertisements->get();
    }

    /**
     * @return Collection
     */
    public function getAdvertisementListForCountries($date)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        $advertisements = $this->addUserSettings($advertisements);

        return $advertisements->get();
    }


    /**
     * @param array $filter
     * @param int $number
     * @param $date
     * @return mixed
     */
    public function getAdvertisementListForGuest(array $filter, int $number, $date)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('advertisements.type', $filter['advertisement_type'])
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        $advertisements = $this->addUserSettings($advertisements);

        if (!empty($filter['city_code'])) {
            $advertisements = $advertisements->where(function ($query) use ($filter) {
                $query->where('advertisements.city_ext_code', $filter['city_code'])
                    ->orwhere('u.city_id', $filter['city_code'])
                    ->orwhere('companies.city_id', $filter['city_code']);

                if (!empty($filter['city_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(advertisements.city)'), Str::lower($filter['city_name']))
                        ->orwhere(DB::raw('lower(u.city)'), Str::lower($filter['city_name']))
                        ->orwhere(DB::raw('lower(companies.city)'), Str::lower($filter['city_name']));
                }
            });
        }

        if (!empty($filter['country_code'])) {
            $advertisements = $advertisements->where(function ($query) use ($filter) {
                $query->where('advertisements.country_ext_code', $filter['country_code'])
                    ->orwhere('u.country_id', $filter['country_code'])
                    ->orwhere('companies.country_id', $filter['country_code']);

                if (!empty($filter['country_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(advertisements.country)'), Str::lower($filter['country_name']))
                        ->orwhere(DB::raw('lower(u.country)'), Str::lower($filter['country_name']))
                        ->orwhere(DB::raw('lower(companies.country)'), Str::lower($filter['country_name']));
                }
            });
        }

        if ($number > 0)
            $advertisements = $advertisements->limit($number);

        return $advertisements->orderBy('advertisements.created_at', 'DESC')
            //->limit($number)
            ->get();
    }


    /**
     * @param $author
     * @param $date
     * @param null $categoryKey
     * @param null $advertisementType
     * @return mixed
     */
    public function getAuthorAllAdvertisements($author, $date, $categoryKey = null, $advertisementType = null)
    {
        $authorAllAdvertisements = $this->getAdvertisementQueryBuilder()
            ->where('advertisements.user_id', $author->user_id)
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        if (!empty($author->company_id))
            $authorAllAdvertisements = $authorAllAdvertisements->where('advertisements.company_id', $author->company_id);
        else
           $authorAllAdvertisements = $authorAllAdvertisements->whereNull('advertisements.company_id');

        if (!empty($categoryKey))
            $authorAllAdvertisements = $authorAllAdvertisements->where('categories.key', $categoryKey);

        if (!empty($advertisementType))
            $authorAllAdvertisements = $authorAllAdvertisements->where('advertisements.type', $advertisementType);

        return $authorAllAdvertisements->get();
    }

    /**
     * @param array $childCategoryIds
     * @param array $filter
     * @param $date
     * @return mixed
     */
    public function getAdvertisementsByCategory(array $childCategoryIds, array $filter, $date)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('categories.key', $filter['category_key'])
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        if (!empty($childCategoryIds))
            $advertisements = $advertisements->whereIn('advertisements.child_category_id', $childCategoryIds);

        $advertisements = $this->addFilter($advertisements, $filter);
        $advertisements = $this->addUserSettings($advertisements);

        return $advertisements->get();
    }

    /**
     * @param array $filter
     * @param $date
     * @return mixed
     */
    public function getAdvertisementsBySearch(array $filter, $date)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false);

        if (!empty($filter['city_code'])) {
            if (empty($filter['order']))
                $advertisements = $advertisements->orderBy('advertisements.is_top_country_at', 'DESC');

            $advertisements = $advertisements->where(function ($query) use ($filter) {
                $query->where('advertisements.city_ext_code', $filter['city_code'])
                    ->orwhere('u.city_id', $filter['city_code'])
                    ->orwhere('companies.city_id', $filter['city_code']);

                if (!empty($filter['city_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(advertisements.city)'), Str::lower($filter['city_name']))
                        ->orwhere(DB::raw('lower(u.city)'), Str::lower($filter['city_name']))
                        ->orwhere(DB::raw('lower(companies.city)'), Str::lower($filter['city_name']));
                }
            });
        }

        if (!empty($filter['country_code'])) {

            if (empty($filter['city_code']) && empty($filter['order']))
                $advertisements = $advertisements->orderBy('advertisements.is_top_country_at', 'DESC');

            $advertisements = $advertisements->where(function ($query) use ($filter) {
                $query->where('advertisements.country_ext_code', $filter['country_code'])
                    ->orwhere('u.country_id', $filter['country_code'])
                    ->orwhere('companies.country_id', $filter['country_code']);

                if (!empty($filter['country_name']) && App::getLocale() == 'ru') {
                    $query = $query->orwhere(DB::raw('lower(advertisements.country)'), Str::lower($filter['country_name']))
                        ->orwhere(DB::raw('lower(u.country)'), Str::lower($filter['country_name']))
                        ->orwhere(DB::raw('lower(companies.country)'), Str::lower($filter['country_name']));
                }
            });
        }

        if (!empty($filter['category_key']))
            $advertisements = $advertisements->where('categories.key', $filter['category_key']);


        if (!empty($filter['search'])) {
            if (empty($filter['search_type'])) {
                $advertisements = $advertisements->where(function ($query) use ($filter) {
                    $query->whereJsonContains('advertisements.hashtags', $filter['search'])
                        ->orwhere(DB::raw('lower(advertisements.description)'), 'like', '%' . Str::lower($filter['search']) . '%')
                        ->orWhere(DB::raw('lower(advertisements.title)'), 'like', '%' . Str::lower($filter['search']) . '%');
                });
            } elseif ($filter['search_type'] == SearchTypeEnum::ADVERTISEMENT) {
                $advertisements = $advertisements->where(function ($query) use ($filter) {
                    $query->where(DB::raw('lower(advertisements.description)'), 'like', '%' . Str::lower($filter['search']) . '%')
                        ->orWhere(DB::raw('lower(advertisements.title)'), 'like', '%' . Str::lower($filter['search']) . '%');
                });
            } elseif ($filter['search_type'] == SearchTypeEnum::HASHTAGS)
                $advertisements = $advertisements->whereJsonContains('advertisements.hashtags', $filter['search']);

        }

        $advertisements = $this->addFilter($advertisements, $filter);
        $advertisements = $this->addUserSettings($advertisements);

        return $advertisements->get();

    }

    /**
     * @param $countryCode
     * @param $countryName
     * @param $number
     * @param $date
     * @param null $advertisementType
     * @return mixed
     */
    public function getAdvertisementsTop($countryCode, $countryName, $number, $date, $advertisementType = null)
    {
        $advertisements = $this->getAdvertisementQueryBuilder()
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->where('advertisements.is_hide', false)
            ->where('advertisements.is_top_country_at', '>=', new DateTime('NOW'))
            ->orderBy('advertisements.is_top_country_at', 'DESC')
            ->limit($number);


        $advertisements = $advertisements->where(function ($query) use ($countryCode, $countryName) {
            $query->where('advertisements.country_ext_code', $countryCode)
                ->orwhere('u.country_id', $countryCode)
                ->orwhere('companies.country_id', $countryCode);

            if (!empty($countryName) && App::getLocale() == 'ru') {
                $query = $query->orwhere(DB::raw('lower(advertisements.country)'), Str::lower($countryName))
                    ->orwhere(DB::raw('lower(u.country)'), Str::lower($countryName))
                    ->orwhere(DB::raw('lower(companies.country)'), Str::lower($countryName));
            }
        });


        if (!empty($advertisementType))
            $advertisements = $advertisements->where('advertisements.type', $advertisementType);

        $advertisements = $this->addUserSettings($advertisements);
        return $advertisements->get();
    }

    /**
     * @param int $advertisementId
     * @return mixed
     */
    public function getAdvertisementComments(int $advertisementId)
    {
        return AdvertisementComment::join('users as u', 'u.id', '=', 'advertisement_comments.user_id')
            ->select('u.name', 'u.avatar', 'advertisement_comments.message', 'advertisement_comments.created_at')
            ->where('advertisement_comments.advertisement_id', $advertisementId)
            ->orderBy('advertisement_comments.created_at', 'DESC')
            ->get();
    }

    public function addUserSettings($advertisements)
    {
        $advertisements->join('user_settings as us', 'us.user_id', '=', 'u.id')
            ->where(function ($query) {

                $query
                    ->where(function ($query_1) {
                        $query_1->where('us.is_hide_user', false)
                            ->where('us.is_hide_company', false);
                    })
                    ->orwhere(function ($query_2) {
                        $query_2->whereNull('advertisements.company_id')
                            ->where('us.is_hide_user', false)
                            ->where('us.is_hide_company', true);
                    })
                    ->orwhere(function ($query_3) {
                        $query_3->whereNotNull('advertisements.company_id')
                            ->where('us.is_hide_user', true)
                            ->where('us.is_hide_company', false);
                    });

            });

        return $advertisements;
    }

    /**
     * @return mixed
     */
    private function getAdvertisementQueryBuilder()
    {
        return Advertisement::join('currencies as c', 'c.id', '=', 'advertisements.currency_id')
            ->join('categories', 'categories.id', '=', 'advertisements.category_id')
            ->join('users as u', 'u.id', '=', 'advertisements.user_id')
            ->leftjoin('companies', 'companies.id', '=', 'advertisements.company_id')
            ->select(
                'advertisements.id as advertisement_id',
                'advertisements.user_id',
                'advertisements.company_id',
                'advertisements.title',
                'advertisements.price',
                'advertisements.price_type',
                'advertisements.payment',
                'advertisements.child_category_id as child_category_id',
                'advertisements.hashtags',
                'advertisements.reach_audience',
                'advertisements.amount',
                'advertisements.length',
                'advertisements.width',
                'advertisements.travel_abroad',
                'advertisements.ready_for_political_advertising',
                'advertisements.date_start',
                'advertisements.date_finish',
                'advertisements.date_of_the',
                'advertisements.deadline_date',
                'advertisements.sample',
                'advertisements.link_page',
                'advertisements.attendance',
                'advertisements.type as advertisement_type',

                'advertisements.is_allocate_at',
                'advertisements.is_top_country_at',
                'advertisements.is_urgent_at',

                'advertisements.photo_id',

                'c.id as currency_id',
                'c.code as currency_code',
                'c.name as currency_name',

                'categories.id as category_id',
                'categories.type as category_type',

                'advertisements.city as advertisement_city',
                'advertisements.city_ext_code as advertisement_city_ext_code',
                'advertisements.latitude as advertisement_latitude',
                'advertisements.longitude as advertisement_longitude',
                'advertisements.country as advertisement_country',
                'advertisements.country_ext_code as advertisement_country_ext_code',

                'u.name as user_name',
                'u.city as user_city',
                'u.latitude as user_latitude',
                'u.longitude as user_longitude',
                'u.country as user_country',
                'u.country_id as user_country_id',
                'u.city_id as user_city_id',

                'companies.name as company_name',
                'companies.city as company_city',
                'companies.latitude as company_latitude',
                'companies.longitude as company_longitude',
                'companies.country as company_country',
                'companies.country_id as company_country_id',
                'companies.city_id as company_city_id',
                'companies.is_verification as company_is_verification'

            )
            ->whereNull('advertisements.deleted_at')
            ->whereNull('u.deleted_at')
            ->whereNull('companies.deleted_at');
    }

    /**
     * @param $advertisements
     * @param array $filter
     * @return mixed
     */
    private function addFilter($advertisements, array $filter)
    {
        if (!empty($filter['payment_ids']))
            $advertisements = $advertisements->whereIn('advertisements.payment', $filter['payment_ids']);

        if (!empty($filter['price_from']))
            $advertisements = $advertisements->where('advertisements.price', '>=', $filter['price_from']);

        if (!empty($filter['price_to']))
            $advertisements = $advertisements->where('advertisements.price', '<=', $filter['price_to']);

        if (!empty($filter['amount_from']))
            $advertisements = $advertisements->where('advertisements.amount', '>=', $filter['amount_from']);

        if (!empty($filter['amount_to']))
            $advertisements = $advertisements->where('advertisements.amount', '<=', $filter['amount_to']);

        if (!empty($filter['length_from']))
            $advertisements = $advertisements->where('advertisements.length', '>=', $filter['length_from']);

        if (!empty($filter['length_to']))
            $advertisements = $advertisements->where('advertisements.length', '<=', $filter['length_to']);

        if (!empty($filter['width_from']))
            $advertisements = $advertisements->where('advertisements.width', '>=', $filter['width_from']);

        if (!empty($filter['width_to']))
            $advertisements = $advertisements->where('advertisements.width', '<=', $filter['width_to']);

        if (!empty($filter['reach_audience_from']))
            $advertisements = $advertisements->where('advertisements.reach_audience', '>=', $filter['reach_audience_from']);

        if (!empty($filter['reach_audience_to']))
            $advertisements = $advertisements->where('advertisements.reach_audience', '<=', $filter['reach_audience_to']);

        if (!empty($filter['travel_abroad']) && in_array($filter['travel_abroad'], [true, 'true', 1, '1'], true))
            $advertisements = $advertisements->where('advertisements.travel_abroad', 2);

        if (!empty($filter['ready_for_political_advertising']) &&
            in_array($filter['ready_for_political_advertising'], [true, 'true', 1, '1'], true)) {

            $advertisements = $advertisements->where('advertisements.ready_for_political_advertising', 2);
        }

        if (!empty($filter['photo_report']) && in_array($filter['photo_report'], [true, 'true', 1, '1'], true))
            $advertisements = $advertisements->where('advertisements.photo_report', 2);

        if (!empty($filter['make_and_place_advertising']) &&
            in_array($filter['make_and_place_advertising'], [true, 'true', 1, '1'], true)) {

            $advertisements = $advertisements->where('advertisements.make_and_place_advertising', 2);
        }

        if (!empty($filter['advertisement_type']))
            $advertisements = $advertisements->where('advertisements.type', $filter['advertisement_type']);

        if (!empty($filter['person_type'])) {
            if ($filter['person_type'] == PersonTypeEnum::COMPANY)
                $advertisements = $advertisements->whereNotNull('advertisements.company_id');

            if ($filter['person_type'] == PersonTypeEnum::PRIVATE_PERSON)
                $advertisements = $advertisements->whereNull('advertisements.company_id');
        }

        if (!empty($filter['order'])) {
            switch ($filter['order']) {
                case "price_asc":
                    $advertisements = $advertisements->orderBy('advertisements.price', 'ASC');
                    break;
                case "price_desc":
                    $advertisements = $advertisements->orderBy('advertisements.price', 'DESC');
                    break;
                case "popular_asc":
                    $advertisements = $advertisements->orderBy('advertisements.views_total', 'ASC');
                    break;
                case "popular_desc":
                    $advertisements = $advertisements->orderBy('advertisements.views_total', 'DESC');
                    break;
            }
        }

        return $advertisements;
    }

}
