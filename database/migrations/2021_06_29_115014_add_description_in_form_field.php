<?php

use App\Models\Category;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDescriptionInFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $description = FormField::firstOrCreate(['name'=>'Описание', 'key'=>'description', 'type'=>'textarea']);
        FrontVariablesLang::firstOrCreate(['key' => 'description', 'value' => 'Описание']);

        $categories = Category::get();

        $categories->each(function ($category) use ($description) {
            DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $description->id]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $description = FormField::where('key', 'description')->first();

        DB::table('category_form_field')->where('form_field_id', $description->id)->delete();
        $description->delete();
    }
}
