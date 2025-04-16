<?php

namespace App\Observers;

use App\Models\Image;
use App\Models\UserImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as IMG;

class UserImageObserver
{
    /**
     * Handle the Image "creating" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function creating(UserImage $image)
    {

    }

    /**
     * Handle the Image "created" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function created(UserImage $image)
    {

    }

    /**
     * Handle the Image "updated" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function updated(UserImage $image)
    {
        //
    }

    /**
     * Handle the Image "deleted" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function deleted(UserImage $image)
    {



    }
    /**
     * Handle the Image "deleting" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function deleting(UserImage $image)
    {
//        dd($image);
//        dd('deleted',public_path('storage/'.$image->photo_url));
        File::delete(public_path('storage/'.$image->photo_url));
    }

    /**
     * Handle the Image "restored" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function restored(UserImage $image)
    {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     *
     * @param UserImage $image
     * @return void
     */
    public function forceDeleted(UserImage $image)
    {
        //
    }
}
