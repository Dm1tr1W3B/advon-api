<?php

use Illuminate\Support\Str;
use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangedPathPhotoForAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Image::get()
            ->each(function ($image) {
                if (Str::contains($image->photo_url, 'advertisement_additional_photos/')) {
                    $image->photo_url =  'product_additional_photos/' . Str::after($image->photo_url, 'advertisement_additional_photos/');
                    $image->save();
                    return true;
                }



                if (Str::contains($image->photo_url, 'advertisements/')) {
                    $image->photo_url =  'products/' . Str::after($image->photo_url, 'advertisements/');
                    $image->save();
                    return true;
                }


            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Image::get()
            ->each(function ($image) {
                if (Str::contains($image->photo_url, 'product_additional_photos/')) {
                    $image->photo_url =  'advertisement_additional_photos/' . Str::after($image->photo_url, 'product_additional_photos/');
                    $image->save();
                    return true;
                }



                if (Str::contains($image->photo_url, 'products/')) {
                    $image->photo_url =  'advertisements/' . Str::after($image->photo_url, 'products/');
                    $image->save();
                    return true;
                }



            });
    }
}
