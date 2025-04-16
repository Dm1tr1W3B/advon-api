<?php

namespace App\Http\Resources;

use App\Models\OfferYourPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            "id" => $this->id,
            "text" => $this->text,
            "created_at" => $this->created_at,
            "chat_id" => $this->chat_id,
            "files" => MessageFilesResource::collection($this->files),
            "from" => $this->user_id,
            "from_company" => $this->company_id,
            "offer_your_price" => new OfferYourPriceResource(OfferYourPrice::where("message_id",$this->id)->first()),
            "advertisement" => new ShowAdvertisementResource($this->advertisement)

        ];
    }
}
