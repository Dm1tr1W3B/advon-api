<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_form_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('form_field_id')
                ->index()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_form_field');
    }
}
