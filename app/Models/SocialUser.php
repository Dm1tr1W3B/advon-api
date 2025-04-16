<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialUser extends Model
{
    use HasFactory;

    protected $fillable=[
        'social_type',
        'user_id',
        'social_id',
        'name',
        'nickname',
        'email',
        'avatar',
        'token'
    ];
}
