<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['performer','employer']);
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('child_category_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description');
            $table->double('price', 8, 2)->default(0);
            $table->foreignId('currency_id')
                ->default(1)
                ->constrained();
            $table->enum('price_type', [0, 1, 8])
                ->comment('0 торг возможен не выбран, 1 торг возможен выбран, 8 Договорная');
            $table->enum('payment', [0, 1, 2, 3, 4, 5, 6])
                ->default(0)
                ->comment('0 не используется 1 в день, 2 в неделю, 3 в месяц, 4 в год , 5 на 20 лет, 6 навсегда');
            $table->jsonb('hashtags');
            $table->integer('reach_audience')->nullable();
            $table->enum('travel_abroad', [0, 1, 2])->default(0)->comment('0 не используется 1 нет 2 да');
            $table->enum('ready_for_political_advertising', [0, 1, 2])->default(0)->comment('0 не используется 1 нет 2 да');
            $table->enum('photo_report', [0, 1, 2])->default(0)->comment('0 не используется 1 нет 2 да');
            $table->enum('make_and_place_advertising', [0, 1, 2])->default(0)->comment('0 не используется 1 нет 2 да');
            $table->integer('amount');
            $table->double('length', 9, 3)->nullable();
            $table->double('width', 9, 3)->nullable();
            $table->string('video')->nullable()->default('');
            $table->string('sample')->nullable()->default('');
            $table->integer('deadline_date')->nullable();
            $table->string('link_page')->nullable()->default('');
            $table->string('attendance')->nullable()->default('');
            $table->integer('date_of_the')->nullable();
            $table->integer('date_start')->nullable();
            $table->integer('date_finish')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_hide')->default(false);
            $table->string('video_url')->nullable()->default('');
            $table->string('country')->nullable()->default('');
            $table->string('region')->nullable()->default('');
            $table->string('city')->nullable()->default('');
            $table->string('country_ext_code')->nullable()->default('');
            $table->string('region_ext_code')->nullable()->default('');
            $table->string('city_ext_code')->nullable()->default('');
            $table->double('latitude', 16, 11)->default(null)->nullable();
            $table->double('longitude', 16, 11)->default(null)->nullable();
            $table->integer('views_total')->nullable();
            $table->integer('views_today')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
}
