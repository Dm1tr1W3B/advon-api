<?php

use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

class UploudImagesForCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = Category::get();

        $categories->each(function ($category)  {

            $newImage = new Image();
            $newImage->photo_url = 'Categories/'. $category->key . '.png';
            $newImage->real_image = Storage::get('/public/Categories/'. $category->key . '.png');
            $newImage->folder = 'Categories';

            $newImage->name = "original_Categories";
            $newImage->type = 'png';
            $newImage->width = 121;
            $newImage->height = 121;
            $newImage->device_type = "desktop";
            $newImage->save();

            $category->image_id = $newImage->id;
            $category->save();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $categories = Category::get();

        $categories->each(function ($category)  {
            $imageId = $category->image_id;
            $category->image_id = null;
            $category->save();

            Image::where('id', $imageId)->delete();
        });
    }
}
