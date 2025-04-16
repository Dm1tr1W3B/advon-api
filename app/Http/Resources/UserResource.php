<?php

namespace App\Http\Resources;

use App\Models\UserContact;
use App\Models\UserPhone;
use App\Models\UserSocial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'is_full_registration' => $this->is_full_registration,
            'name' => $this->name,
            'email' => empty($this->email) ? '' : $this->email,
            'phone' => empty($this->phone) ? '' : $this->phone,
            'description' => $this->description,
            'avatar' => url('storage/default/user.png'),
            'additional_photos' => ImageResource::collection($this->images),
            'country' => $this->country,
            'region' => $this->region,
            'city' => $this->city,
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'city_id' => $this->city_id,
            'has_company' => false,
            'company_name' => '',
            'balance' => $this->balance,

            'additional_phones' => [],
            'contacts' => [],
            'social_media' => [],

            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'phone_verified_at' => $this->phone_verified_at,
            'email_verified_at' => $this->email_verified_at,
            'blocked' => $this->blocked,
            'created_at' => $this->created_at->format('Y.m.d'),
            'count_of_unread_messages' => count($this->unreadMessages)
        ];

        if (!empty($this->latitude) &&
            !empty($this->longitude) &&
            !empty($this->phone_verified_at) &&
            !empty($this->email_verified_at) &&
            !empty($this->email) &&
            !empty($this->phone)
        ) {
            $result['is_full_registration'] = true;
        }

        $company = $this->company;
        if (!empty($company)) {
            $result['has_company'] = true;
            $result['company_name'] = $company->name;
        }

        $phones = UserPhone::where('user_id', $this->id)->get();
        if ($phones->isNotEmpty())
            $result['additional_phones'] = $phones->pluck('phone')->all();

        $result['contacts'] = UserContact::leftjoin('contact_types as ct', 'ct.id', '=', 'user_contact.contact_id')
            ->leftjoin('images as i', 'i.id', '=', 'ct.image_id')
            ->select('i.photo_url', 'ct.name', 'user_contact.values')
            ->where('user_contact.user_id', $this->id)
            ->get()
            ->map(function ($contact) {
                $contact->photo_url = url('storage/' . $contact->photo_url);
                return $contact;
            });

        $result['social_media'] = UserSocial::leftjoin('social_media_types as smt', 'smt.id', '=', 'user_social.social_id')
            ->leftjoin('images as i', 'i.id', '=', 'smt.image_id')
            ->select('i.photo_url', 'smt.name', 'user_social.values')
            ->where('user_social.user_id', $this->id)
            ->get()->map(function ($socialMedia) {
                $socialMedia->photo_url = url('storage/' . $socialMedia->photo_url);
                return $socialMedia;
            });


        if (!empty($this->avatar) && $this->avatar != 'users/default.png')
            $result['avatar'] = asset(Storage::url($this->avatar));

        return $result;
    }

}
