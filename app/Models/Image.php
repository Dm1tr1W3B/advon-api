<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    public function childImages()
    {
        return $this->hasMany(ChildImage::class, 'original_id');
    }
    public function users(){
        return $this->belongsToMany(User::class,'user_images')->using(UserImage::class)->withPivot('user_id');
    }
    public function companies(){
        return $this->belongsToMany(Company::class,'company_images')->using(UserImage::class)->withPivot('company_id');
    }

}
