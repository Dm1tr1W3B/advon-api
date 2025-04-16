<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferYourPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_your_prices', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->foreignId('currency_id')
                ->default(1)
                ->constrained();
            $table->bigInteger('message_id')->unsigned()->index();
            $table->foreign('message_id')->references('id')->on('chat_message')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_your_prices');
    }
}
