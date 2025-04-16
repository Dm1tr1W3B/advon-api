<?php

use App\Models\SocialMediaType;
use Illuminate\Database\Migrations\Migration;

class MakeSocialMediaTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $types = [
            "Facebook",
            "Instagram",
            "VK",
            "OK",
        ];
        foreach ($types as $type)
            SocialMediaType::create(["name" => $type, "status" => true]);


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
