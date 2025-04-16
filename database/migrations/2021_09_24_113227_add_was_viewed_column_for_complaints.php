<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWasViewedColumnForComplaints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisement_author_complaints', function (Blueprint $table) {
            $table->boolean('was_viewed')->default(false);
        });

        Schema::table('advertisement_complaints', function (Blueprint $table) {
            $table->boolean('was_viewed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisement_author_complaints', function (Blueprint $table) {
            $table->dropColumn(['was_viewed']);
        });

        Schema::table('advertisement_complaints', function (Blueprint $table) {
            $table->dropColumn(['was_viewed']);
        });
    }
}
