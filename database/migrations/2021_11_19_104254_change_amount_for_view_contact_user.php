<?php

use App\Models\Price;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountForViewContactUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Price::create([
            'name' => 'Безлимит',
            'key' => '999999999_view_contact_user',
            'amount' => 4000.00,
            'currency_id' => 1,
            'group' => 'view_contact_user',
            'quantity' => 999999999,
        ]);

        Price::where('key', '10_view_contact_user')
            ->where('group', 'view_contact_user')
            ->update([
                'amount' => 200.00,
            ]);

        Price::where('key', '15_view_contact_user')
            ->where('group', 'view_contact_user')
            ->update([
                'amount' => 300.00,
            ]);

        Price::where('key', '20_view_contact_user')
            ->where('group', 'view_contact_user')
            ->update([
                'amount' => 390.00,
            ]);

        Price::where('key', '50_view_contact_user')
            ->where('group', 'view_contact_user')
            ->update([
                'amount' => 950.00,
            ]);

        Price::where('key', '100_view_contact_user')
            ->where('group', 'view_contact_user')
            ->update([
                'amount' => 1800.00,
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
