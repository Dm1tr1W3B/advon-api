<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OfferYourPrice extends Model
{

    protected $table = "offer_your_prices";
    protected $fillable = [
        "message_id",
        "currency_id",
        "price",
    ];

    public $timestamps = false;
}
