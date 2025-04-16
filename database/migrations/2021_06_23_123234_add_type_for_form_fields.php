<?php

use App\Models\Category;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTypeForFormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->string('type')->default('');
        });

        $negotiable = FormField::firstOrCreate(['name'=>'Договорная', 'key'=>'negotiable', 'type'=>'checkbox']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable', 'value' => 'Договорная']);
        $bargaining = FormField::firstOrCreate(['name'=>'Торг', 'key'=>'bargaining', 'type'=>'checkbox']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining', 'value' => 'Торг']);

        FormField::where('key', 'payment')->update(['type'=>'select']);
        FormField::where('key', 'sample')->update(['type'=>'file']);
        FormField::whereIn('key', ['travel_abroad', 'ready_for_political_advertising', 'photo_report', 'make_and_place_advertising'])->update(['type'=>'checkbox']);
        FormField::whereIn('key', ['deadline_date', 'date_of_the', 'date_start', 'date_finish'])->update(['type'=>'datetime']);
        FormField::where('type', '')->update(['type'=>'text']);

        $categories = Category::get();

        $categories->each(function ($category) use ($negotiable, $bargaining) {
            DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $negotiable->id]);
            DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $bargaining->id]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        $negotiable = FormField::where('key', 'negotiable')->first();
        $bargaining = FormField::where('key', 'bargaining')->first();

        DB::table('category_form_field')->where('form_field_id', $negotiable->id)->delete();
        DB::table('category_form_field')->where('form_field_id', $bargaining->id)->delete();

        $negotiable->delete();
        $bargaining->delete();

    }
}
