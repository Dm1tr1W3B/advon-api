<?php

use App\Models\FeedbackType;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->integer('display_order');
            $table->timestamps();
        });

        FeedbackType::create(['name' => 'Проблемы с сайтом', 'key' => 'problems-with-the-site', 'display_order' => 1]);
        FrontVariablesLang::firstOrCreate(['key' => 'problems-with-the-site', 'value' => 'Проблемы с сайтом']);

        FeedbackType::create(['name' => 'Деньги', 'key' => 'money', 'display_order' => 2]);
        FrontVariablesLang::firstOrCreate(['key' => 'money', 'value' => 'Деньги']);

        FeedbackType::create(['name' => 'Сотрудничество, идеи', 'key' => 'collaboration-ideas', 'display_order' => 3]);
        FrontVariablesLang::firstOrCreate(['key' => 'collaboration-ideas', 'value' => 'Сотрудничество, идеи']);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_types');
    }
}
