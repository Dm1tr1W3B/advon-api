<?php

use App\Http\Helpers\ImageHelper;
use App\Models\SocialMediaType;
use App\Models\SocialType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageForSocialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_types', function (Blueprint $table) {
            $table->string('image')
                ->nullable();
        });

        SocialType::where('active',true)->get()->each(function ($socialMediaTypey) {
            $path = '/storage/social_media_type/' . $socialMediaTypey->key . '.svg';

            $socialMediaTypey->image = $path;
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
        Schema::table('social_types', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
