<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChildCategoryPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Page::create(['name' => 'Подкатегория',  'key' => 'child_category']);
        Page::create(['name' => 'Локация',  'key' => 'location']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Page::whereIn('name', ['Подкатегория','Локация'])->delete();
    }
}
