<?php

use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Models\FrontVariablesLang;
use App\Models\TransactionBalanceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslateTransactionBalanceType3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FrontVariablesLang::firstOrCreate(['key' => 'increase_limit_view_contact_user', 'value' => 'Увеличение лимита просмотра контактов']);

        TransactionBalanceType::truncate();

        foreach (TransactionBalanceTypeEnum::KEYS as $k => $v) {
            TransactionBalanceType::create([
                'id' => $k,
                'name' => FrontVariablesLang::where('key', $v)->first()->value,
                'key' => $v,

            ]);
        }
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
