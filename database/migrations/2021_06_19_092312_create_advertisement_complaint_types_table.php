<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementComplaintTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_complaint_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('advertisement_complaint_id')->unsigned()->index();
            $table->foreign('advertisement_complaint_id')->references('id')->on('advertisement_complaints')->onDelete('cascade');
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
        Schema::dropIfExists('advertisement_complaint_types');
    }
}
