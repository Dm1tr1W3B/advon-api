<?php

use App\Models\Price;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->index();
            $table->double('amount', 8, 2)->default(0);
            $table->foreignId('currency_id')
                ->default(1)
                ->constrained();
            $table->string('group')->nullable()->default('');
            $table->integer('quantity')->default(0)->nullable();
            $table->timestamps();
        });

        Price::create([
            'name' => '1 объявление',
            'key' => '1_advertisement',
            'amount' => 250.00,
            'currency_id' => 1,
            'group' => '',
            'quantity' => 1,
        ]);

        Price::create([
            'name' => '5 объявлений',
            'key' => '5_advertisement',
            'amount' => 1200.00,
            'currency_id' => 1,
            'group' => 'additional_advertisement',
            'quantity' => 5,
        ]);

        Price::create([
            'name' => '10 объявлений',
            'key' => '10_advertisement',
            'amount' => 2300.00,
            'currency_id' => 1,
            'group' => 'additional_advertisement',
            'quantity' => 10,
        ]);

        Price::create([
            'name' => '20 объявлений',
            'key' => '20_advertisement',
            'amount' => 4500.00,
            'currency_id' => 1,
            'group' => 'additional_advertisement',
            'quantity' => 20,
        ]);

        Price::create([
            'name' => '50 объявлений',
            'key' => '50_advertisement',
            'amount' => 11500.00,
            'currency_id' => 1,
            'group' => 'additional_advertisement',
            'quantity' => 50,
        ]);


        Price::create([
            'name' => 'Продвижение компание. Пакет Закрепление',
            'key' => 'company_top',
            'amount' => 200.00,
            'currency_id' => 1,
            'group' => ''
        ]);

        Price::create([
            'name' => 'Продвижение компание. Пакет Выделение',
            'key' => 'company_allocate',
            'amount' => 199.00,
            'currency_id' => 1,
            'group' => ''
        ]);

        Price::create([
            'name' => 'Продвижение объявления. Пакет Поднятие по всей стране',
            'key' => 'advertisement_top_country',
            'amount' => 190.00,
            'currency_id' => 1,
            'group' => ''
        ]);

        Price::create([
            'name' => 'Продвижение объявления. Пакет Выделение',
            'key' => 'advertisement_allocate',
            'amount' => 199.00,
            'currency_id' => 1,
            'group' => ''
        ]);

        Price::create([
            'name' => 'Продвижение объявления. Пакет Срочно',
            'key' => 'advertisement_urgent',
            'amount' => 187.00,
            'currency_id' => 1,
            'group' => ''
        ]);

        Price::create([
            'name' => 'Продвижение объявления. Пакет Турбо',
            'key' => 'advertisement_turbo',
            'amount' => 450.00,
            'currency_id' => 1,
            'group' => ''
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
