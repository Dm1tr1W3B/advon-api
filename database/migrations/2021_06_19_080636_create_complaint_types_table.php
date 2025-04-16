<?php

use App\Models\ComplaintType;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->integer('display_order');
            $table->timestamps();
        });

        ComplaintType::create(['name' => 'Неверная рубрика', 'key' => 'invalid-rubric', 'display_order' => 1]);
        FrontVariablesLang::firstOrCreate(['key' => 'invalid-rubric', 'value' => 'Неверная рубрика']);

        ComplaintType::create(['name' => 'Запрещенный товар/услуга', 'key' => 'prohibited-product-service', 'display_order' => 2]);
        FrontVariablesLang::firstOrCreate(['key' => 'prohibited-product-service', 'value' => 'Запрещенный товар/услуга']);

        ComplaintType::create(['name' => 'Объявление неактуально', 'key' => 'advertisement-not-relevant', 'display_order' => 3]);
        FrontVariablesLang::firstOrCreate(['key' => 'advertisement-not-relevant', 'value' => 'Объявление неактуально']);

        ComplaintType::create(['name' => 'Неверный адрес', 'key' => 'wrong-address', 'display_order' => 4]);
        FrontVariablesLang::firstOrCreate(['key' => 'wrong-address', 'value' => 'Неверный адрес']);

        ComplaintType::create(['name' => 'Другое', 'key' => 'other', 'display_order' => 5]);
        FrontVariablesLang::firstOrCreate(['key' => 'other', 'value' => 'Другое']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_types');
    }
}
