<?php

namespace App\Repositories;


use App\Models\Banner;
use App\Models\ContactType;
use App\Models\SEO;
use App\Models\SocialMediaType;
use App\Models\SocialType;
use Illuminate\Database\Eloquent\Collection;


class CommonRepository
{
    /**
     * @return Collection
     */
    public function getSocialMediaTypes()
    {
        return SocialMediaType::leftjoin('images as i', 'i.id', '=', 'social_media_types.image_id')
            ->select(
                'social_media_types.id as id',
                'social_media_types.name as name',
                'i.photo_url'
            )
            ->get();
    }

    /**
     * @return Collection
     */
    public function getSocialLogins()
    {
        return SocialType::where('active', true)->get();
    }

    /**
     * @return Collection
     */
    public function getContactType()
    {
        return ContactType::leftjoin('images as i', 'i.id', '=', 'contact_types.image_id')
            ->select(
                'contact_types.id as id',
                'contact_types.name as name',
                'i.photo_url'
            )
            ->get();
    }

    /**
     * @param int $pageId
     * @return mixed
     */
    public function getBanners(int $pageId)
    {
        return Banner::where('page_id', $pageId)
            ->where('is_active', true)
            ->orderBy('display_order', 'ASC')
            ->get(['name', 'file', 'alt', 'url', 'display_order']);
    }

    /**
     * @param int $pageId
     * @param string $locale
     * @return mixed
     */
    public function getSeo(int $pageId, string $locale)
    {
        return SEO::join('languages as l', 'l.id', '=', 's_e_o_s.language_id')
            ->select(
                's_e_o_s.title',
                's_e_o_s.description',
                's_e_o_s.seo_text'
            )
            ->where('s_e_o_s.page_id', $pageId)
            ->where('l.key', $locale)
            ->orderBy('s_e_o_s.created_at', 'DESC')
            ->first();
    }


}
