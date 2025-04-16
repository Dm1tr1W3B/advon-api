<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'name',
        'file',
        'alt',
        'url',
        'display_order',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
