<?php

use App\Models\Bonus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('bonus_type_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->double('amount', 8, 2)->default(0);
            $table->foreignId('currency_id')
                ->default(1)
                ->constrained();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_real_balance')->default(false);
            $table->timestamps();
        });

        Bonus::create([
            'name' => 'Бонус за регистрацию на реальный баланс',
            'bonus_type_id' => 1,
            'amount' => 200.00,
            'currency_id' => 1,
            'is_active' => true,
            'is_real_balance' => true,
        ]);

        Bonus::create([
            'name' => 'Реферальный бонус на реальный баланс',
            'bonus_type_id' => 2,
            'amount' => 1.00,
            'currency_id' => 1,
            'is_active' => true,
            'is_real_balance' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuses');
    }
}
