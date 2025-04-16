<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('owner_id')->unsigned()->index();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->bigInteger('photo_id')->nullable();
            $table->foreign('photo_id')->references('id')->on('images');

            $table->text('description')->nullable()->default('');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->double('latitude', 16, 11)->default(null)->nullable();
            $table->double('longitude', 16, 11)->default(null)->nullable();
            $table->string('site_url')->nullable();
            $table->string('hashtags')->nullable();
            $table->string('audio')->nullable();
            $table->string('video_url')->nullable();
            $table->string('document')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
