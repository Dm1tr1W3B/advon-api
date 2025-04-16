<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedIsAllocateAndIsTopForCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->timestamp('is_top_at')->useCurrent()
                ->comment('дата завершения пакета закрепление');
            $table->timestamp('is_allocate_at')->useCurrent()
                ->comment('дата завершения пакета выделение');
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
            $table->dropColumn(['is_allocate_at', 'is_top_at']);
        });
    }
}
