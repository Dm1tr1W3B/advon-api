<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixHintsPromoFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category_telo = Category::where('type', 'employer')
            ->where('key', 'telo')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_telo->id)->update(['value' => 'Сами изготовим и разместим рекламу?']);

        $category_sz = Category::where('type', 'employer')
            ->where('key', 'strizhka-zhivotnyh')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_sz->id)->update(['value' => 'Сами изготовим и разместим рекламу?']);

        $category_stritart = Category::where('type', 'employer')
            ->where('key', 'stritart')
            ->first();
        FrontVariablesLang::where('key', 'amount_' . $category_stritart->id)->update(['value' => 'Требуемое количество рекламных поверхностей']);

        $category_lh = Category::where('type', 'employer')
            ->where('key', 'lichnyje-veshhi')
            ->first();

        ChildCategory::where('category_id', $category_lh->id)->get()
            ->each(function ($childCategory) use ($category_lh) {
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_lh->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу?']);
            });



        $category_dze = Category::where('type', 'employer')
            ->where('key', 'doma-i-zdanija-eksterjer')
            ->first();

        ChildCategory::where('category_id', $category_dze->id)->get()
            ->each(function ($childCategory) use ($category_dze) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);


                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Политическая реклама?']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Напишите подробнее о вашей промокампании.']);

                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Требуемое количество рекламных поверхностей']);
                FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);
            });

        $category_pi = Category::where('type', 'employer')
            ->where('key', 'pomeshhenija-interjer')
            ->first();

        ChildCategory::where('category_id', $category_pi->id)->get()
            ->each(function ($childCategory) use ($category_pi) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);


                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Политическая реклама?']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Напишите подробнее о вашей промокампании.']);

                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Требуемое количество рекламных поверхностей']);
                FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);
            });


        $category_sp = Category::where('type', 'employer')
            ->where('key', 'sobstvennaja-produkcija')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_sp->id, 'value' => 'Требуемое количество']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_sp->id, 'value' => '']);

        $category_brk = Category::where('type', 'employer')
            ->where('key', 'bilbordy-reklamnyje-konstrukcii')
            ->first();

        FrontVariablesLang::where('key', 'description_hint_' . $category_brk->id)->update(['value' => 'Напишите подробнее о вашей рекламе, на какой срок и тд.']);

        $category_nm = Category::where('type', 'employer')
            ->where('key', 'nestandartnoje-mesto')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_nm->id, 'value' => 'Требуемое количество исполнителей']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_nm->id, 'value' => '']);

        $category_ot = Category::where('type', 'employer')
            ->where('key', 'obshhestvennyj-transport')
            ->first();

        $childCategory = ChildCategory::where('category_id', $category_ot->id)
            ->where('key', 'drugoje')->first();

        FrontVariablesLang::where('key', 'amount_' . $category_ot->id . '_' . $childCategory->id)->update(['value' => 'Требуемое количество транспорта*']);

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
