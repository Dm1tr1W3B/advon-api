<?php

namespace App\Repositories;

use App\Models\Advertisement;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\UserContact;
use App\Models\UserSocial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UserRepository
{
    /**
     * @param $userId
     * @return Collection
     */
    public function getUserContacts($userId)
    {
        return UserContact::leftjoin('contact_types as ct', 'ct.id', '=', 'user_contact.contact_id')
            ->leftjoin('images as i', 'i.id', '=', 'ct.image_id')
            ->select('i.photo_url', 'ct.name', 'user_contact.values')
            ->where('user_contact.user_id', $userId)
            ->get();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserSocial($userId)
    {
        return UserSocial::leftjoin('social_media_types as smt', 'smt.id', '=', 'user_social.social_id')
            ->leftjoin('images as i', 'i.id', '=', 'smt.image_id')
            ->select('i.photo_url', 'smt.name', 'user_social.values')
            ->where('user_social.user_id', $userId)
            ->get();
    }

    /**
     * @param $user
     * @param $date
     * @param null $categoryKey
     * @return mixed
     */
    public function gettAdvertisementActive($user, $date, $categoryKey = null)
    {
        $advertisements = Advertisement::join('categories', 'categories.id', '=', 'advertisements.category_id')
            ->select('categories.key as category_key', DB::raw('count(*) as advertisement_active'))
            ->where('advertisements.user_id', $user->id)
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date)
            ->groupBy('categories.key');

        if (!empty($categoryKey))
            $advertisements = $advertisements->where('categories.key', $categoryKey);

        return $advertisements->get();
    }

    /**
     * @param $user
     * @param null $categoryKey
     * @return mixed
     */
    public function getUserCategories($user, $categoryKey = null)
    {
        $userCategory = UserCategory::select('category_key', DB::raw('sum(number_advertisement) as limit'))
            ->where('user_id', $user->id)
            ->groupBy('category_key');

        if (!empty($categoryKey))
            $userCategory = $userCategory->where('category_key', $categoryKey);

        return $userCategory->get();
    }

}
