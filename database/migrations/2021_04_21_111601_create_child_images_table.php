<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_images', function (Blueprint $table) {
            $table->id();
            $table->text("name");
            $table->text("photo_url");
            $table->text("type");
            $table->bigInteger('original_id')->unsigned()->index();
            $table->foreign('original_id')->references('id')->on('images')->onDelete('cascade');
            $table->integer("width");
            $table->integer("height");
            $table->text("device_type")->nullable();

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
        Schema::dropIfExists('child_images');
    }
}
