<?php

use App\Models\Price;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowForPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Price::create([
            'name' => '10',
            'key' => '10_view_contact_user',
            'amount' => 100.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 10,
        ]);

        Price::create([
            'name' => '15',
            'key' => '15_view_contact_user',
            'amount' => 150.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 15,
        ]);

        Price::create([
            'name' => '20',
            'key' => '20_view_contact_user',
            'amount' => 200.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 20,
        ]);

        Price::create([
            'name' => '50',
            'key' => '50_view_contact_user',
            'amount' => 450.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 50,
        ]);

        Price::create([
            'name' => '100',
            'key' => '50_view_contact_user',
            'amount' => 800.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 100,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
