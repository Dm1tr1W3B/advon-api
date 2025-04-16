<?php

use App\Models\PaymentSystem;
use App\Models\PaymentSystemCurrency;
use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_system_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_system_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('currency_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });

        $paymentSystem = PaymentSystem::where('name', 'robokassa')->first();
        $currency = Currency::where('code', 'RUB')->first();

        PaymentSystemCurrency::create([
            'payment_system_id' => $paymentSystem->id,
            'currency_id' => $currency->id,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_system_currencies');
    }
}
