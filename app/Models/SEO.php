<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;

    protected $table='s_e_o_s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'page_id',
        'title',
        'description',
        'seo_text',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
