<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as IMG;

class ImageObserver
{
    /**
     * Handle the Image "creating" event.
     *
     * @param Image $image
     * @return void
     */
    public function creating(Image $image)
    {

        $img = IMG::make($image->real_image);

        if (!File::exists(public_path('storage/' . $image->folder))) {
            File::makeDirectory(public_path('storage/' . $image->folder), 0755, true);
        }
        $img->save(public_path('storage/' . $image->photo_url));
        unset($image->folder, $image->real_image);
    }

    /**
     * Handle the Image "created" event.
     *
     * @param Image $image
     * @return void
     */
    public function created(Image $image)
    {

    }

    /**
     * Handle the Image "updated" event.
     *
     * @param Image $image
     * @return void
     */
    public function updated(Image $image)
    {
        //
    }

    /**
     * Handle the Image "deleted" event.
     *
     * @param Image $image
     * @return void
     */
    public function deleted(Image $image)
    {


    }

    /**
     * Handle the Image "deleting" event.
     *
     * @param Image $image
     * @return void
     */
    public function deleting(Image $image)
    {

//        dd('deleted',public_path('storage/'.$image->photo_url));
        File::delete(public_path('storage/' . $image->photo_url));

    }

    /**
     * Handle the Image "restored" event.
     *
     * @param Image $image
     * @return void
     */
    public function restored(Image $image)
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     *
     * @param Image $image
     * @return void
     */
    public function forceDeleted(Image $image)
    {
        //
    }
}
