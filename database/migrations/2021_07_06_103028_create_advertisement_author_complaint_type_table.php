<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementAuthorComplaintTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_author_complaint_type', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('advertisement_author_complaint_id')->unsigned()->index();
            $table->foreign('advertisement_author_complaint_id')->references('id')->on('advertisement_author_complaints')->onDelete('cascade');
            $table->bigInteger('complaint_type_id')->unsigned();
            $table->foreign('complaint_type_id')->references('id')->on('complaint_types');
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
        Schema::dropIfExists('advertisement_author_complaint_type');
    }
}

