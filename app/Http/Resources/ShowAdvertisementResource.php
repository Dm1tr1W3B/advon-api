<?php

namespace App\Http\Resources;

use App\Http\Enums\AdvertisementPaymentEnum;
use App\Http\Enums\PersonTypeEnum;
use App\Http\Enums\PriceTypeEnum;
use App\Models\Image;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ShowAdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $objDateTime = new DateTime($this->created_at);
        $dateTime = new DateTime('NOW');

        $result = [
            'advertisement_id' => $this->id,
            'advertisement_type' => $this->type,
            'person_type' =>  $this->person_type,
            'title' => $this->title,
            'description' => $this->description,
            'additional_photos' => collect([[
                'id' => 7777777,
                'photo_url' => url('storage/default/product.png'),
                'type' => 'png'
            ]]),
            'currency' => $this->currency,
            'number_views_all' => $this->number_views_all,
            'number_views_day' => $this->number_views_day,
            'number_advertisement' => $this->number_advertisement,
            'person' => $this->person,
            'created_at' => $objDateTime->format('d.m.Y'),
            'city' => $this->city,
            'country' => $this->country,
            'is_favorite' => $this->is_favorite,
            // 'formField' => $this->formFields,
            'is_allocate' => new DateTime($this->is_allocate_at) > $dateTime,
            'is_top_country' => new DateTime($this->is_top_country_at) > $dateTime,
            'is_urgent' => new DateTime($this->is_urgent_at) > $dateTime,
            'category' => $this->category,
            'child_category' => !empty($this->child_category) ? $this->child_category : null,

        ];

        $images = $this->images;
        if ($images->isNotEmpty())
            $result['additional_photos'] = ImageResource::collection($images);

        foreach ($this->formFields as $k => $formField) {

            if ($k == 'price_group')
                continue;

            if (!$formField->is_show)
                continue;

            $key = $formField->key;

            $result[$key] = $this->$key;

            if ($formField->type == 'checkbox')
                $result[$key] = $this->$key == 2 ? true : false;

            if ($key == 'sample')
                $result['sample'] = empty($this->sample) ? '' : url('storage/' . $this->sample);

            $result[$key . '_name'] = $formField->name;

            if (in_array($key,['length', 'width'])) {
                $result[$key . '_hint'] = $formField->hint;

                if ($formField->hint == 'мм')
                    $result[$key] = 10 * $result[$key];

            }

            if (in_array($key,['deadline_date', 'date_of_the', 'date_start', 'date_finish'])) {
                $result[$key] = date("d.m.Y", $this->$key);
            }
        }

        $paymentName = AdvertisementPaymentEnum::NONE;
        if (!empty(AdvertisementPaymentEnum::ALL[(int)$this->payment]) && (int)$this->payment > 0)
            $paymentName = __('advertisement.' . AdvertisementPaymentEnum::ALL[(int)$this->payment]);

        $result['payment'] = $paymentName;

        $result['price_type'] = __('advertisement.' . PriceTypeEnum::ALL[$this->price_type]);

        $result['price'] = $this->price;
        $result['price_name'] = $this->formFields['price_group']['price']->name;

        return $result;
    }
}
