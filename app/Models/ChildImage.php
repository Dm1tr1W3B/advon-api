<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ChildImage extends Model
{
    protected $table = 'child_images';

    public function image()
    {
        return $this->belongsTo(Image::class,'original_id');
    }

}
