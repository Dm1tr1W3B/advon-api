<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('payment_system_id')->index()
                ->constrained()
                ->onDelete('cascade');
            $table->double('amount', 10, 2);
            $table->foreignId('currency_id')
                ->constrained()
                ->onDelete('cascade');
            $table->integer('status');
            $table->text('description')->nullable()->default('');
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
        Schema::dropIfExists('deposits');
    }
}
