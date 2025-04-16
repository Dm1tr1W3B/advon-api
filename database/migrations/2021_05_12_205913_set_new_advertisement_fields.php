<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetNewAdvertisementFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->bigInteger('photo_id')->nullable();
            $table->foreign('photo_id')->references('id')->on('images');
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('advertisements', function (Blueprint $table) {
//            $table->bigInteger('photo_id')->nullable();
//            $table->dropForeign('photo_id');
//
//        });
    }
}
