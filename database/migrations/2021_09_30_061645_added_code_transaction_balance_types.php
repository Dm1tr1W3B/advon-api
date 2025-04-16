<?php

use App\Models\TransactionBalanceType;
use App\Http\Enums\TransactionBalanceTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedCodeTransactionBalanceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_balance_types', function (Blueprint $table) {
            $table->integer('code')->default(0);
        });

        foreach (TransactionBalanceTypeEnum::KEYS as $k => $v) {
            TransactionBalanceType::where('key', $v)->update([
               'code' =>  $k
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
        Schema::table('transaction_balance_types', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
