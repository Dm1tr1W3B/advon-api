<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSocial extends Pivot
{
    public $primaryKey = "social_id";

    protected $table = 'user_social';
}
