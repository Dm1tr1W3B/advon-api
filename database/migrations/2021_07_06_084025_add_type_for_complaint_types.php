<?php

use App\Models\ComplaintType;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeForComplaintTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_types', function (Blueprint $table) {
           $table->integer('type')
               ->default(3)->comment('1 - user, 2 - company, 3 - advertisement');
        });

        ComplaintType::create(['name' => 'Неверная рубрика', 'key' => 'invalid-rubric', 'display_order' => 1, 'type' => 2]);
        ComplaintType::create(['name' => 'Запрещенный товар/услуга', 'key' => 'prohibited-product-service', 'display_order' => 2, 'type' => 2]);
        ComplaintType::create(['name' => 'Неверный адрес', 'key' => 'wrong-address', 'display_order' => 3, 'type' => 2]);
        ComplaintType::create(['name' => 'Другое', 'key' => 'other', 'display_order' => 4, 'type' => 2]);

        ComplaintType::create(['name' => 'Человек редиска', 'key' => 'radish-man', 'display_order' => 1, 'type' => 1]);
        FrontVariablesLang::firstOrCreate(['key' => 'radish-man', 'value' => 'Человек редиска']);

        ComplaintType::create(['name' => 'Содержит запрещенную информацию', 'key' => 'invalid-rubric', 'display_order' => 2, 'type' => 1]);
        FrontVariablesLang::firstOrCreate(['key' => 'Contains-prohibited-information', 'value' => 'Содержит запрещенную информацию']);

        ComplaintType::create(['name' => 'Другое', 'key' => 'other', 'display_order' => 3, 'type' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ComplaintType::whereIn('type', [1,2])->delete();

        Schema::table('complaint_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
