<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'key',
        'num_left',
        'num_right',
        'keyword',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
