<?php

use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslateTransactionBalanceType2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            FrontVariablesLang::firstOrCreate(['key' => 'admin_bonus_registration_real', 'value' => 'Администратор начислел бонус за регистрацию']);
            FrontVariablesLang::firstOrCreate(['key' => 'admin_bonus_referral_real', 'value' => 'Администратор начислел бонус за приглашенного человека']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            //
        });
    }
}
