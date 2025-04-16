<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedIsAllocateAndIsTopCountryAndIsUrgentForAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {

            $table->timestamp('is_top_country_at')->useCurrent()
                ->comment('дата завершения пакета поднятие по всей стране');
            $table->timestamp('is_allocate_at')->useCurrent()
                ->comment('дата завершения пакета выделение');
            $table->timestamp('is_urgent_at')->useCurrent()
                ->comment('дата завершения пакета срочно');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['is_allocate_at', 'is_top_country_at', 'is_urgent_at']);
        });
    }
}
