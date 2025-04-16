<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key'); // для локализации
            $table->enum('type', ['performer','employer']);
            $table->integer('num_left');
            $table->integer('num_right');
            $table->foreignId('image_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->string('descriptions')->default('')->nullable();
            $table->string('title_ograph')->default('')->nullable();
            $table->string('keyword')->default('')->nullable(); // для совместимости со старой бд
            $table->timestamps();
        });

        Schema::create('child_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('key'); // для локализации
            $table->integer('num_left');
            $table->integer('num_right');
            $table->string('keyword')->default('')->nullable(); // для совместимости со старой бд
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
        Schema::dropIfExists('child_categories');
        Schema::dropIfExists('categories');
    }
}
