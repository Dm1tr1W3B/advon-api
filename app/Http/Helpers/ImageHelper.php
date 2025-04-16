<?php


namespace App\Http\Helpers;


use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageHelper
{
    static function createPhotoFromRequest($folder, UploadedFile $photo, $name = "", $device = "")
    {
        $filename = Str::random(20);
        $extension = $photo->getClientOriginalExtension();
        $date = new Carbon();
        $date->locale('en');
        $folder = "$folder/" . $date->monthName . $date->year;

        $path = "$folder/$filename.$extension";
        [$width, $height] = getimagesize($photo->getRealPath());
        //Создаем запись  о новой оригинальной картинке
        $newImage = new Image();
        $newImage->photo_url = $path;
        $newImage->real_image = $photo;
        $newImage->folder = $folder;

        $newImage->name = $name ? $name : "original_$folder";
        $newImage->type = $photo->getClientOriginalExtension();
        $newImage->width = $width;
        $newImage->height = $height;
        $newImage->device_type = $device ? $device : "desktop";

        /**
         * Перед записью создается фотография в  app/Observers/ImageObserver::creating()
         */
        $newImage->save();
        return $newImage;
    }

    static function createPhotoFromURL($folder, string $photo, $name = "", $device = "")
    {
        $filename = Str::random(20);
        $extension = "jpg";
        $date = new Carbon();
        $date->locale('en');
        $folder = "$folder/" . $date->monthName . $date->year;

        $path = "$folder/$filename.$extension";
        [$width, $height] = [100, 100];
        //Создаем запись  о новой оригинальной картинке
        $newImage = new Image();
        $newImage->photo_url = $path;
        $newImage->real_image = $photo;
        $newImage->folder = $folder;

        $newImage->name = $name ? $name : "original_$folder";
        $newImage->type = $extension;
        $newImage->width = $width;
        $newImage->height = $height;
        $newImage->device_type = $device ? $device : "desktop";

        /**
         * Перед записью создается фотография в  app/Observers/ImageObserver::creating()
         */
        $newImage->save();
        return $newImage;
    }

}
