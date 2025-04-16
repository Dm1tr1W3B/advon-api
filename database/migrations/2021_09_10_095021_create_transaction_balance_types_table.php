<?php

use App\Models\TransactionBalanceType;
use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionBalanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_balance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->timestamps();
        });

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
        Schema::dropIfExists('transaction_balance_types');
    }
}
