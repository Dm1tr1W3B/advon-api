<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanySocial extends Pivot
{
    public $primaryKey = "social_id";

    protected $table = 'company_social';
}
