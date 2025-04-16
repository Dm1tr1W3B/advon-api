<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SocialMediaType extends Model
{

    protected $table = "social_media_types";
    protected $fillable = ["name", "status"];
}
