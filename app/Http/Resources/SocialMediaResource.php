<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {


        $value = $this->whenPivotLoaded('user_social', function () {
            return $this->pivot->values;
        });
        $value = $value ? $value : $this->whenPivotLoaded('company_social', function () {
            return $this->pivot->values;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $value,
        ];
    }
}
