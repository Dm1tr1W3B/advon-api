<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyContact extends Pivot
{
    use HasFactory;
    protected $table='company_contact';
    public $primaryKey = "contact_id";

}