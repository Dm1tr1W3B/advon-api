<?php

use App\Models\SocialMediaType;
use App\Http\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageIdForSocialMediaTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_media_types', function (Blueprint $table) {
            $table->foreignId('image_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
        });

        SocialMediaType::get()->each(function ($socialMediaTypey) {
            $path = Storage::path('/public/social_media_type/'. $socialMediaTypey->name . '.png');

            $image = ImageHelper::createPhotoFromURL('social_media_type', $path);

            if (!isset($image->id))
                return true;

            $socialMediaTypey->image_id = $image->id;
            $socialMediaTypey->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_media_types', function (Blueprint $table) {
            $table->dropColumn('image_id');
        });
    }
}
