<?php

namespace App\Http\Resources;

use App\Http\Helpers\CompanyHelper;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{



    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'description' => $this->description,
            'photo' => (new ImageResource(Image::find($this->photo_id))),
            'additional_photos' => ImageResource::collection($this->images),
            'hashtags' => explode(',', $this->hashtags),
            'site_url' => $this->site_url,
            'audio' => $this->audio ? url($this->audio) : null,
            'video_url' => $this->video_url,
            'document' => $this->document ? url($this->document) : null,
            'country' => $this->country,
            'region' => $this->region,
            'city' => $this->city,
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'city_id' => $this->city_id,
            'social_media' => ContactResource::collection($this->social),
            'contacts' => ContactResource::collection($this->contacts),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at->format('Y.m.d'),
            'owner' => new UserResource($this->user),

        ];
    }
}
