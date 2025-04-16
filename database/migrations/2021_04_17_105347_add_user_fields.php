<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('description')->nullable()->default('');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->double('balance', 10, 2)->default(0.00)->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->double('latitude', 16, 11)->default(null)->nullable();
            $table->double('longitude', 16, 11)->default(null)->nullable();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->boolean('is_full_registration')->default(false)->nullable();
            $table->boolean('blocked')->default(false)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
