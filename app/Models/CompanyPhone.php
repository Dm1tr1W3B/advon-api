<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPhone extends Model
{
    use HasFactory;

    protected $table = "company_phone";
    public $primaryKey = "company_id";

    protected $fillable = [
       'company_id',
       'phone'
    ];
}
