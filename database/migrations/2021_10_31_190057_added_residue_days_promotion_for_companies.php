<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedResidueDaysPromotionForCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('top_residue_days')->default(0)
                ->comment('пакета  оставшиеся дни при скрытии ');
            $table->integer('allocate_residue_days')->default(0)
                ->comment('пакета выделение  оставшиеся дни при скрытии');

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
            $table->dropColumn(['top_residue_days', 'allocate_residue_days']);
        });
    }
}
