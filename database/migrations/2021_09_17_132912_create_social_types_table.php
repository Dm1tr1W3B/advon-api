<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSocialTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('social_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->boolean('active')->default(true);
        });
        $social_types = [
            [
                'name' => 'Facebook',
                'key' => 'facebook',
                'active' => true

            ],
            [
                'name' => 'Google',
                'key' => 'google',
                'active' => true

            ],

            [
                'name' => 'VK',
                'key' => 'vkontakte',
                'active' => true

            ],
            [
                'name' => 'Одноклассники',
                'key' => 'odnoklassniki',
                'active' => true

            ],
            [
                'name' => 'Twitter',
                'key' => 'twitter',
                'active' => false
            ],
            [
                'name' => 'Instagram',
                'key' => 'instagram',
                'active' => false
            ],

        ];
        DB::table('social_types')->insert($social_types);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_types');
    }
}
