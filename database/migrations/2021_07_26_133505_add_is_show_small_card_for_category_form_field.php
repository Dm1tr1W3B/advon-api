<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsShowSmallCardForCategoryFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_form_field', function (Blueprint $table) {
            $table->boolean('is_show_small_card')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_form_field', function (Blueprint $table) {
            $table->dropColumn('is_show_small_card');
        });
    }
}
