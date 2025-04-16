<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('deposit_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->integer('type');

            $table->foreignId('currency_id')
                ->default(1)
                ->constrained();
            $table->double('amount', 8, 2);
            $table->double('balance', 8, 2);
            $table->double('new_balance', 8, 2);
            $table->text('description');
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
        Schema::dropIfExists('transaction_balances');
    }
}
