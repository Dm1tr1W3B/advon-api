<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'is_hide_user',
        'is_hide_company',
        'is_receive_news',
        'is_receive_messages_by_email',
        'is_receive_comments_by_email',
        'is_receive_price_favorite_by_email',
        'is_receive_messages_by_phone',
        'is_receive_comments_by_phone',
        'is_receive_price_favorite_by_phone',
    ];
}
