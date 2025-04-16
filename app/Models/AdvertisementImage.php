<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdvertisementImage extends Pivot
{
    public $primaryKey   = "image_id";

    protected $table='advertisement_images';

    public function delete()
    {
        $this->fireModelEvent('deleted', true);

        $del= Image::destroy($this->image_id);
    }
}
