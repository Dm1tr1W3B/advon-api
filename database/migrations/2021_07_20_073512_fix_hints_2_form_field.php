<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixHints2FormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category_lv = Category::where('type', 'performer')
            ->where('key', 'lichnyje-veshhi')
            ->first();
        $childCategorys_lv = ChildCategory::where('category_id', $category_lv->id)->get();
        $childCategorys_lv->each(function ($childCategory) use ($category_lv) {

            FrontVariablesLang::where('key', 'ready_for_political_advertising_' . $category_lv->id . '_' . $childCategory->id)->update(['value' => 'Готов к политической рекламе']);

        });

        $category_landshaft = Category::where('type', 'performer')
            ->where('key', 'landshaft')
            ->first();
        ChildCategory::where('category_id', $category_lv->id)
            ->get()
            ->each(function ($childCategory) use ($category_landshaft) {
                FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_landshaft->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            });

        $category_nm = Category::where('type', 'performer')
            ->where('key', 'nestandartnoje-mesto')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_nm->id , 'value' => 'см']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_nm->id, 'value' => 'см']);

        $category_sp = Category::where('type', 'performer')
            ->where('key', 'sobstvennaja-produkcija')
            ->first();
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_sp->id, 'value' => 'Напишите подробнее о вашей акции, какая продукция интересует, и тд.']);


        $category_mediaprojekty = Category::where('type', 'performer')
            ->where('key', 'mediaprojekty')
            ->first();

        ChildCategory::where('category_id', $category_mediaprojekty->id)->get()
            ->each(function ($childCategory) use ($category_mediaprojekty) {
                FrontVariablesLang::where('key', 'ready_for_political_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id)->update(['value' => 'Это политическая реклама?']);
            });

        $category_nm = Category::where('type', 'performer')
            ->where('key', 'nestandartnoje-mesto')
            ->first();
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_nm->id, 'value' => 'Напишите подробнее о вашей акции, что ищете, на какой срок и тд.']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
