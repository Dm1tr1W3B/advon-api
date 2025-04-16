<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdvertisementComplaintType extends Pivot
{
    use HasFactory;
    protected $table='advertisement_complaint_types';

}
