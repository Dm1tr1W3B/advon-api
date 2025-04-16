<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CityRegionCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('country_id')->nullable();
            $table->string('region_id')->nullable();
            $table->string('city_id')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('country_id')->nullable();
            $table->string('region_id')->nullable();
            $table->string('city_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'country_id',
                'region_id',
                'city_id'
            ]);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country_id',
                'region_id',
                'city_id'
            ]);
        });
    }
}
