<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Http\Helpers\ImageHelper;

class AddCompanyAdditionalPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldShopsImages = DB::connection('old_advon')
            ->table("bff_shops_images")
            ->distinct()
            ->get();

        Company::get()->each(function ($company) use ($oldShopsImages) {

            $photo_ids = [];
            $oldShopsImages->where('user_id', $company->owner_id)
               ->each(function ($shopsImages) use (&$photo_ids) {

                   $oldPath = 'https://advon.me/files/images/shops/'. $shopsImages->dir.'/'.$shopsImages->item_id.'o'.$shopsImages->filename;

                   $image = ImageHelper::createPhotoFromURL('companies/old', $oldPath);

                   if (!isset($image->id))
                       return true;

                   $photo_ids[] = $image->id;
               });

            $company->images()->attach($photo_ids);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
