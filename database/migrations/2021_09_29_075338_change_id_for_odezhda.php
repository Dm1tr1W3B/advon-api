<?php

use App\Models\ChildCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdForOdezhda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ChildCategory::where('id', 1)->update(['id' => 61]);

        Schema::table('advertisements', function (Blueprint $table) {
            $table->bigInteger('deadline_date')->nullable()->change();
            $table->bigInteger('date_of_the')->nullable()->change();
            $table->bigInteger('date_start')->nullable()->change();
            $table->bigInteger('date_finish')->nullable()->change();
            $table->bigInteger('amount')->default(0)->change();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ChildCategory::where('id', 61)->update(['id' => 1]);

        /*
        Schema::table('advertisements', function (Blueprint $table) {
            $table->integer('deadline_date')->nullable()->change();
            $table->integer('date_of_the')->nullable()->change();
            $table->integer('date_start')->nullable()->change();
            $table->integer('date_finish')->nullable()->change();
        });
        */
    }
}
