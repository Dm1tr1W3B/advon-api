<?php

namespace App\Http\Resources;

use App\Http\Enums\PriceTypeEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class EditAdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result['advertisement'] =  [
            'advertisement_id' => $this->id,
            'advertisement_type' => $this->type,
            'person_type' => $this->person_type,
            'category' => $this->category,
            'child_category' => empty($this->child_category) ? [] : $this->child_category,
            'title' => $this->title,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'additional_photos' => ImageResource::collection($this->images),
            'currency' => new CurrencyResource($this->currency),
            'is_published' =>  $this->is_published,
            'price' =>  $this->price,
            'payment' =>  $this->payment,
            'country' => empty($this->country) ? '' : $this->country,
            'region' => empty($this->region) ? '' : $this->region,
            'city' => empty($this->city) ? '' : $this->city,
        ];


        foreach ($this->formFields as $k => $formField) {
            if (empty($formField['key']))
                continue;
           
            
            $key = $formField['key'];
            $result['advertisement'][$key] = empty($this->$key) ? '' : $this->$key;

            if ($formField['type'] == 'checkbox')
                $result ['advertisement'][$key] = $this->$key == 2 ? true : false;

            if ($key == 'sample')
                $result ['advertisement']['sample'] = empty($this->sample) ? '' : url('storage/' . $this->sample);
        }


        if ($this->price_type == PriceTypeEnum::NEGOTIABLE_ID)
            $result ['advertisement']['negotiable'] = true;

        if ($this->price_type == PriceTypeEnum::BARGAINING_ID)
            $result ['advertisement']['bargaining'] = true;

        $result['formFields'] = $this->formFields;

        return  $result;
    }
}
