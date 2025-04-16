<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdvertisementAuthorComplaintType extends Pivot
{
    use HasFactory;
    protected $table='advertisement_author_complaint_type';
}
