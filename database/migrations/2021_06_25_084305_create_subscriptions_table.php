<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('recipient_user_id')->unsigned()->index()->comment('пользователь который подписался на получение писем');
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('advertisement_id')->unsigned()->index();
            $table->foreign('advertisement_id')->references('id')->on('advertisements')->onDelete('cascade');

            $table->bigInteger('sender_user_id')->unsigned()->index()->nullable();
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('sender_company_id')->unsigned()->index()->nullable();
            $table->foreign('sender_company_id')->references('id')->on('companies')->onDelete('cascade');

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
        Schema::dropIfExists('subscriptions');
    }
}
