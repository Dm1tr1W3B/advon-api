<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $value = $this->whenPivotLoaded('user_contact', function () {
            return $this->pivot->values;
        });
        $value = $value ? $value : $this->whenPivotLoaded('company_contact', function () {
            return $this->pivot->values;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $value,
        ];
    }
}
