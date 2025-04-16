<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixHintsFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category_telo = Category::where('type', 'performer')
            ->where('key', 'telo')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_telo->id)->update(['value' => 'Сам изготовлю и размещу']);
        FrontVariablesLang::where('key', 'ready_for_political_advertising_' . $category_telo->id)->update(['value' => 'Готов к политической рекламе']);

        $category_sz = Category::where('type', 'performer')
            ->where('key', 'strizhka-zhivotnyh')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_sz->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
        FrontVariablesLang::where('key', 'ready_for_political_advertising_' . $category_sz->id)->update(['value' => 'Готов к политической рекламе']);

        $category_lv = Category::where('type', 'performer')
            ->where('key', 'lichnyje-veshhi')
            ->first();
        $childCategorys_lv = ChildCategory::where('category_id', $category_lv->id)->get();
        $childCategorys_lv->each(function ($childCategory) use ($category_lv) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_lv->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            FrontVariablesLang::where('key', 'travel_abroad_' . $category_lv->id . '_' . $childCategory->id)->update(['value' => 'Выезжаю за границу']);

        });

        $category_mototransport = Category::where('type', 'performer')
            ->where('key', 'mototransport')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_mototransport->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
        FrontVariablesLang::where('key', 'amount_hint_' . $category_mototransport->id)->update(['value' => 'Количество мототранспорта под рекламу']);

        $category_gruzoviki = Category::where('type', 'performer')
            ->where('key', 'gruzoviki')
            ->first();
        $childCategorys_gruzoviki = ChildCategory::where('category_id', $category_gruzoviki->id)->get();
        $childCategorys_gruzoviki->each(function ($childCategory) use ($category_gruzoviki) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            FrontVariablesLang::where('key', 'ready_for_political_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id)->update(['value' => 'Готов к политической рекламе']);

        });

        $category_avtotransport = Category::where('type', 'performer')
            ->where('key', 'avtotransport')
            ->first();
        $childCategorys_avtotransport = ChildCategory::where('category_id', $category_avtotransport->id)->get();
        $childCategorys_avtotransport->each(function ($childCategory) use ($category_avtotransport) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_avtotransport->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            FrontVariablesLang::where('key', 'travel_abroad_' . $category_avtotransport->id . '_' . $childCategory->id)->update(['value' => 'Выезжаю за границу']);
        });



        $category_vt = Category::where('type', 'performer')
            ->where('key', 'vodnaja-tehnika')
            ->first();

        $childCategorys_vt = ChildCategory::where('category_id', $category_vt->id)->get();
        $childCategorys_vt->each(function ($childCategory) use ($category_vt) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_vt->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
        });

        $childCategory = $childCategorys_vt->where('key', 'korabl')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество кораблей под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'jahta')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество яхт под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'malomernyj-kater')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество катеров под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'lodka')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество лодок под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'drugaja-tehnika')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Количество водной техники под рекламу']);

        $category_la = Category::where('type', 'performer')
            ->where('key', 'letatelnyje-apparaty')
            ->first();
        $childCategorys_la = ChildCategory::where('category_id', $category_la->id)->get();
        $childCategorys_la->each(function ($childCategory) use ($category_la) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_la->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
        });

        $category_dze = Category::where('type', 'performer')
            ->where('key', 'doma-i-zdanija-eksterjer')
            ->first();

        ChildCategory::where('category_id', $category_dze->id)->get()
            ->each(function ($childCategory) use ($category_dze) {
                FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_dze->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            });

        $category_pi = Category::where('type', 'performer')
            ->where('key', 'pomeshhenija-interjer')
            ->first();

        ChildCategory::where('category_id', $category_pi->id)->get()
            ->each(function ($childCategory) use ($category_pi) {
                FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_pi->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
            });

        $category_landshaft = Category::where('type', 'performer')
            ->where('key', 'landshaft')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_landshaft->id)->update(['value' => 'Сами изготовите и разместите рекламу']);

        $category_brk = Category::where('type', 'performer')
            ->where('key', 'bilbordy-reklamnyje-konstrukcii')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_brk->id)->update(['value' => 'Сами изготовите и разместите рекламу']);

        $category_mediaprojekty = Category::where('type', 'performer')
            ->where('key', 'mediaprojekty')
            ->first();

        ChildCategory::where('category_id', $category_mediaprojekty->id)->get()
            ->each(function ($childCategory) use ($category_mediaprojekty) {
                FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите рекламу']);
                FrontVariablesLang::where('key', 'reach_audience_' . $category_mediaprojekty->id . '_' . $childCategory->id)->update(['value' => 'Охват']);
            });

        $category_meroprijatija = Category::where('type', 'performer')
            ->where('key', 'meroprijatija')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_meroprijatija->id)->update(['value' => 'Сами изготовите и разместите рекламу']);

        $category_oe = Category::where('type', 'performer')
            ->where('key', 'odezhda-ekipirovka')
            ->first();
        FrontVariablesLang::where('key', 'description_hint_' . $category_oe->id)->update(['value' => 'Опишите подробно каким спортом вы занимаетесь, какая у вас команда, какая рекламная привлекательность вашего места для рекламодателя.']);

        $category_puteshestvija = Category::where('type', 'performer')
            ->where('key', 'puteshestvija')
            ->first();
        FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_puteshestvija->id)->update(['value' => 'Сами изготовите и разместите рекламу']);

         $category_nm = Category::where('type', 'performer')
             ->where('key', 'nestandartnoje-mesto')
             ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_nm->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_nm->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_nm->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_nm->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_nm->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_nm->id, 'value' => 'Сколько людей могут видеть эту рекламу в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_nm->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_nm->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_nm->id, 'value' => 'Сами изготовите и разместите рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_nm->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_nm->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_nm->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_nm->id, 'value' => 'Опишите подробно, где вы хотите разместить рекламу и рекламную привлекательность для рекламодателя']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_nm->id, 'value' => 'Количество предметов для размещения рекламы']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_nm->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_nm->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_puteshestvija->id , 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_nm->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_puteshestvija->id, 'value' => 'см']);

        $category_stroitelstvo = Category::where('type', 'performer')
            ->where('key', 'stroitelstvo')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_stroitelstvo->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_stroitelstvo->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_stroitelstvo->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_stroitelstvo->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_stroitelstvo->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_stroitelstvo->id, 'value' => 'Сколько людей могут видеть эту рекламу в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_stroitelstvo->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_stroitelstvo->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_stroitelstvo->id, 'value' => 'Сами изготовите и разместите рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_stroitelstvo->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_stroitelstvo->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_stroitelstvo->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_stroitelstvo->id, 'value' => 'Опишите подробно ваши планы на строительство или места для рекламы в уже строящихся объектах.']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_stroitelstvo->id, 'value' => 'Количество типовых мест под рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_stroitelstvo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_stroitelstvo->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_stroitelstvo->id , 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_stroitelstvo->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_stroitelstvo->id, 'value' => 'см']);

        $category_ot = Category::where('type', 'performer')
            ->where('key', 'obshhestvennyj-transport')
            ->first();

        $childCategorys_ot = ChildCategory::where('category_id', $category_ot->id)->get();

        $childCategorys_ot->each(function ($childCategory) use ($category_ot) {
            FrontVariablesLang::where('key', 'make_and_place_advertising_' . $category_ot->id . '_' . $childCategory->id)->update(['value' => 'Сами изготовите и разместите рекламу']);
        });
        $travelAbroad = FormField::where('key','travel_abroad')->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category_ot->id, $travelAbroad->id]);
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
