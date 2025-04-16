<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class GetAdvertisementCommentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'avatar' => $this->avatar ? asset(Storage::url($this->avatar)) : null,
            'created_at' => $this->created_at->format('d.m.Y'),
            'message' => $this->message,
        ];
    }
}
