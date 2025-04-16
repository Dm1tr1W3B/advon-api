<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserReferral extends Pivot
{
    public $primaryKey = "referral_id";

    protected $table = 'user_referral';
}
