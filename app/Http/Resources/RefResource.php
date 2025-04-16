<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefResource extends JsonResource
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
            "referrals_count" => count($this->referrals),
            "bonus_balance" => $this->bonus_balance,
            "ref_code" => $this->ref_code,
        ];
    }
}
