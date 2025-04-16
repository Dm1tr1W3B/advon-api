<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;


class FrontVariablesLang extends Model
{
    use Translatable;
    protected $table='front_variables_lang';
    protected $translatable = ['value'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',

    ];

}
