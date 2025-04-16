<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'type',
        'num_left',
        'num_right',
        'image_id',
        'descriptions',
        'title_ograph',
        'keyword',
        'limit',
        'period', // максимальный лимит
    ];
}
