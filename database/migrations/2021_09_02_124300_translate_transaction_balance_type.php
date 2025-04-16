<?php

use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslateTransactionBalanceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_deposit', 'value' => 'Пополнение счета']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_company_top', 'value' => 'Продвижение компание Пакет Закрепление']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_company_allocate', 'value' => 'Продвижение компание Пакет Выделение']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_advertisement_top_country', 'value' => 'Продвижение объявления Пакет Поднятие по всей стране']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_advertisement_allocate', 'value' => 'Продвижение объявления Пакет Выделение']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_advertisement_urgent', 'value' => 'Продвижение объявления Пакет Срочно']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_advertisement_turbo', 'value' => 'Продвижение объявления Пакет Турбо']);

        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_increase_limit_advertisement_category', 'value' => 'Увеличение лимита объявлений для категории']);
        FrontVariablesLang::firstOrCreate(['key' => 'balance_type_admin_change_balance', 'value' => 'Начислено администратором']);

        FrontVariablesLang::firstOrCreate(['key' => 'replenishment_from_bonus_balance', 'value' => 'Пополнение с бонусного баланса']);
        FrontVariablesLang::firstOrCreate(['key' => 'withdrawal_from_bonus_balance', 'value' => 'Вывод с бонусного баланса']);

        FrontVariablesLang::firstOrCreate(['key' => 'bonus_registration', 'value' => 'Бонус за регистрацию']);
        FrontVariablesLang::firstOrCreate(['key' => 'bonus_referral', 'value' => 'Бонус за приглашенного человека']);
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
