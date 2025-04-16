<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedResidueDaysPromotionForAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->integer('top_country_residue_days')->default(0)
                ->comment('пакета поднятие по всей стране оставшиеся дни при скрытии деактивации');
            $table->integer('allocate_residue_days')->default(0)
                ->comment('пакета выделение  оставшиеся дни при скрытии деактивации');
            $table->integer('urgent_residue_days')->default(0)
                ->comment('пакета срочно  оставшиеся дни при скрытии деактивации');
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
            $table->dropColumn(['top_country_residue_days', 'allocate_residue_days', 'urgent_residue_days']);
        });
    }
}
