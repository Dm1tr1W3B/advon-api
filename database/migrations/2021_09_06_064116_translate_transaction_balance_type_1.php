<?php

use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslateTransactionBalanceType1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FrontVariablesLang::firstOrCreate(['key' => 'bonus_registration_real', 'value' => 'Бонус за регистрацию']);
        FrontVariablesLang::firstOrCreate(['key' => 'bonus_referral_real', 'value' => 'Бонус за приглашенного человека']);
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
