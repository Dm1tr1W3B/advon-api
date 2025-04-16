<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddHintsFormField extends Migration
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

        FrontVariablesLang::where('key', 'travel_abroad_' . $category_telo->id)->update(['value' => 'Выезжаю за границу']);

        $category_lichnyjeVeshhi = Category::where('type', 'performer')
            ->where('key', 'lichnyje-veshhi')
            ->first();

        $childCategorys_lv = ChildCategory::where('category_id', $category_lichnyjeVeshhi->id)->get();

        $childCategorys_lv->each(function ($childCategory) use ($category_lichnyjeVeshhi) {
            FrontVariablesLang::where('key', 'travel_abroad_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id)
                ->update(['value' => 'Выезжаю за границу']);
            FrontVariablesLang::where('key', 'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id)
                ->update(['value' => 'Количество штук']);
            FrontVariablesLang::where('key', 'length' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id)
                ->update(['key' => 'length_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id]);


        });


        $category_sz = Category::where('type', 'performer')
            ->where('key', 'strizhka-zhivotnyh')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_sz->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_sz->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_sz->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_sz->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_sz->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_sz->id, 'value' => 'Сколько людей смогут увидеть ваше животное в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_sz->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_sz->id, 'value' => 'Выезжает ли животное за границу']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_sz->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_sz->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_sz->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_sz->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_sz->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_sz->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_sz->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_sz->id, 'value' => 'Напишите, где гуляет ваше животное, опишите рекламную привлекательность рекламодателям.']);

        $category_mototransport = Category::where('type', 'performer')
            ->where('key', 'mototransport')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_mototransport->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_mototransport->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_mototransport->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_mototransport->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_mototransport->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_mototransport->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_mototransport->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_mototransport->id, 'value' => 'Выезжаю за границу']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_mototransport->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_mototransport->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_mototransport->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_mototransport->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_mototransport->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_mototransport->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_mototransport->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_mototransport->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_mototransport->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_mototransport->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_mototransport->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_mototransport->id, 'value' => 'Опишите ваше средство: где и как часто вы пользуетесь этим транспортом, рекламную привлекательность для рекламодателей. Напишите выезжаете ли вы за границу или другие регионы.']);

        $category_gruzoviki = Category::where('type', 'performer')
            ->where('key', 'gruzoviki')
            ->first();

        $childCategorys_gruzoviki = ChildCategory::where('category_id', $category_gruzoviki->id)->get();

        $childCategorys_gruzoviki->each(function ($childCategory) use ($category_gruzoviki) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Выезжаете ли за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Опишите ваше средство: где и как часто вы пользуетесь этим транспортом, рекламную привлекательность для рекламодателей. Напишите выезжаете ли вы за границу или другие регионы.']);

            FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
            FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_gruzoviki->id . '_' . $childCategory->id, 'value' => 'Количество грузовиков под рекламу']);

        });

        $category_avtotransport = Category::where('type', 'performer')
            ->where('key', 'avtotransport')
            ->first();

        $childCategorys_avtotransport = ChildCategory::where('category_id', $category_avtotransport->id)->get();

        $childCategorys_avtotransport->each(function ($childCategory) use ($category_avtotransport) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Выезжаете ли за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Опишите ваше средство: где и как часто вы пользуетесь этим транспортом, рекламную привлекательность для рекламодателей. Напишите выезжаете ли вы за границу или другие регионы.']);

            FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
            FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_avtotransport->id . '_' . $childCategory->id, 'value' => 'Количество авто под рекламу']);

        });


        $category_vt = Category::where('type', 'performer')
            ->where('key', 'vodnaja-tehnika')
            ->first();

        $childCategorys_vt = ChildCategory::where('category_id', $category_vt->id)->get();

        $childCategorys_vt->each(function ($childCategory) use ($category_vt) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Выезжаете ли за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Опишите ваш транспорт: где и как часто вы пользуетесь этим транспортом, рекламную привлекательность для рекламодателей. Напишите выезжаете ли вы за границу или другие регионы.']);

            FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_vt->id . '_' . $childCategory->id, 'value' => 'см']);
        });

        $childCategory = $childCategorys_vt->where('key', 'korabl')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество кораблей под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'jahta')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество яхт под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'malomernyj-kater')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество катеров под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'lodka')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество лодок под рекламу']);

        $childCategory = $childCategorys_vt->where('key', 'drugaja-tehnika')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $childCategory->id . '_' . $childCategory->id, 'value' => 'Количество водной техники под рекламу']);

        $category_la = Category::where('type', 'performer')
            ->where('key', 'letatelnyje-apparaty')
            ->first();


        $childCategorys_la = ChildCategory::where('category_id', $category_la->id)->get();

        $childCategorys_la->each(function ($childCategory) use ($category_la) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Вылетает ли Л/А за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Опишите ваш транспорт: где и как часто вы пользуетесь этим транспортом, рекламную привлекательность для рекламодателей. Напишите выезжаете ли вы за границу или другие регионы.']);

            FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'см']);
        });

        $childCategory = $childCategorys_la->where('key', 'samolety')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество самолётов под рекламу']);

        $childCategory = $childCategorys_la->where('key', 'vertolety')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество вертолётов под рекламу']);

        $childCategory = $childCategorys_la->where('key', 'paraplany-deltaplany')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество парапланов под рекламу']);

        $childCategory = $childCategorys_la->where('key', 'drugije')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_la->id . '_' . $childCategory->id, 'value' => 'Количество Л/А под рекламу']);

        $category_dze = Category::where('type', 'performer')
            ->where('key', 'doma-i-zdanija-eksterjer')
            ->first();

        ChildCategory::where('category_id', $category_dze->id)->get()
            ->each(function ($childCategory) use ($category_dze) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Охват']);
                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

                FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Оплата']);
                FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Предоставлю фотоотчет']);
                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Опишите расположение здания, его видимость и рекламную привлекательность для рекламодателя.']);

                FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Длина']);
                FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'см']);

                FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'Ширина']);
                FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_dze->id . '_' . $childCategory->id, 'value' => 'см']);
            });

        $category_pi = Category::where('type', 'performer')
            ->where('key', 'pomeshhenija-interjer')
            ->first();

        ChildCategory::where('category_id', $category_pi->id)->get()
            ->each(function ($childCategory) use ($category_pi) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Охват']);
                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

                FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Оплата']);
                FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Предоставлю фотоотчет']);
                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Опишите расположение места, его проходимость и рекламную привлекательность для рекламодателя.']);

                FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Длина']);
                FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'см']);

                FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'Ширина']);
                FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_pi->id . '_' . $childCategory->id, 'value' => 'см']);
            });

        $category_brk = Category::where('type', 'performer')
            ->where('key', 'bilbordy-reklamnyje-konstrukcii')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_brk->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_brk->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_brk->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_brk->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_brk->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_brk->id, 'value' => 'Сколько людей смогут видеть эту рекламу в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_brk->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_brk->id, 'value' => 'Предоставлю фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_brk->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_brk->id, 'value' => 'Количество рекламных конструкций под рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_brk->id, 'value' => 'Лучше создавайте одно объявление для одного рекламного места']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_brk->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_brk->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_brk->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_brk->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_brk->id, 'value' => 'Опишите расположение места, его проходимость и рекламную привлекательность для рекламодателя.']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_brk->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_brk->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_brk->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_brk->id, 'value' => 'см']);

        $category_sp = Category::where('type', 'performer')
            ->where('key', 'sobstvennaja-produkcija')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_sp->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_sp->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_sp->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_sp->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_sp->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_sp->id, 'value' => 'Сколько людей смогут увидеть эту рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_sp->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_sp->id, 'value' => 'Предоставлю фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_sp->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_sp->id, 'value' => 'Количество продукции']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_sp->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_sp->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_sp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_sp->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_sp->id, 'value' => 'Опишите продукцию, сколько её выпускается, на какую аудиторию рассчитано и идёт ли она за границу. ']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_sp->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_sp->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_sp->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_sp->id, 'value' => 'см']);

        $category_landshaft = Category::where('type', 'performer')
            ->where('key', 'landshaft')
            ->first();

        $amount = FormField::where('key', 'amount')->first();

        DB::table('category_form_field')
            ->where('category_id', $category_landshaft->id)
            ->where('form_field_id', $amount->id)
            ->delete();

        ChildCategory::where('category_id', $category_landshaft->id)->get()
            ->each(function ($childCategory) use ($category_landshaft) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Охват аудитории']);
                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

                FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Оплата']);
                FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Предоставлю фотоотчет']);
                FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Опишите расположение места, его проходимость и рекламную привлекательность для рекламодателя.']);

                FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Длина']);
                FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'см']);

                FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'Ширина']);
                FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_landshaft->id . '_' . $childCategory->id, 'value' => 'см']);
            });

        $category_mediaprojekty = Category::where('type', 'performer')
            ->where('key', 'mediaprojekty')
            ->first();

        ChildCategory::where('category_id', $category_mediaprojekty->id)->get()
            ->each(function ($childCategory) use ($category_mediaprojekty) {
                FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Цена']);
                FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
                FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
                FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Охват аудитории']);
                FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут видеть эту рекламу?']);

                FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Оплата']);
                FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Сами изготовим']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Торг']);
                FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Описание']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Опишите где хотите вставить рекламу. На какую аудиторию рассчитан проект. Для каких стран?']);

                FrontVariablesLang::firstOrCreate(['key' => 'video_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Видео']);
                FrontVariablesLang::firstOrCreate(['key' => 'video_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

                FrontVariablesLang::firstOrCreate(['key' => 'deadline_date_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Дата дедлайна']);
                FrontVariablesLang::firstOrCreate(['key' => 'deadline_date_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Дата, позже которой заявки на рекламу не принимаются']);

                FrontVariablesLang::firstOrCreate(['key' => 'sample_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => 'Сэмпл']);
                FrontVariablesLang::firstOrCreate(['key' => 'sample_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id, 'value' => '']);

            });


        $category_sss = Category::where('type', 'performer')
            ->where('key', 'socialnyje-seti-i-sajty')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_sss->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_sss->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_sss->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_sss->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_sss->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_sss->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'attendance_' . $category_sss->id, 'value' => 'Посещаемость']);
        FrontVariablesLang::firstOrCreate(['key' => 'attendance_hint_' . $category_sss->id, 'value' => 'Сколько людей смогут видеть эту рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_sss->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_sss->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_sss->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_sss->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_sss->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_sss->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_sss->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_sss->id, 'value' => 'Опишите подробно, где и как разместить рекламу в ваших соцсетях и сайтах.']);

        FrontVariablesLang::firstOrCreate(['key' => 'link_page_' . $category_sss->id, 'value' => 'Ссылка на страницу']);
        FrontVariablesLang::firstOrCreate(['key' => 'link_page_hint_' . $category_sss->id, 'value' => '']);

        $category_meroprijatija = Category::where('type', 'performer')
            ->where('key', 'meroprijatija')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_meroprijatija->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_meroprijatija->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_meroprijatija->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_meroprijatija->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_meroprijatija->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_meroprijatija->id, 'value' => 'Сколько людей смогут видеть эту рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_meroprijatija->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_meroprijatija->id, 'value' => '']);


        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_meroprijatija->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_meroprijatija->id, 'value' => 'Количество штук']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_meroprijatija->id, 'value' => 'Сколько таких мест под рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_meroprijatija->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_meroprijatija->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_meroprijatija->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_meroprijatija->id, 'value' => 'Опишите, где и когда хотите разместить рекламу на вашем будущем мероприятии']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_meroprijatija->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_meroprijatija->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_meroprijatija->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_meroprijatija->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_start_' . $category_meroprijatija->id, 'value' => 'Дата начала']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_start_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_' . $category_meroprijatija->id, 'value' => 'Дата окончания']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_hint_' . $category_meroprijatija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_of_the_' . $category_meroprijatija->id, 'value' => 'Дата проведения']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_of_the_hint_' . $category_meroprijatija->id, 'value' => '']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $category_sz = Category::where('type', 'performer')
            ->where('key', 'strizhka-zhivotnyh')
            ->first();

        $category_mototransport = Category::where('type', 'performer')
            ->where('key', 'mototransport')
            ->first();

        $category_brk = Category::where('type', 'performer')
            ->where('key', 'bilbordy-reklamnyje-konstrukcii')
            ->first();

        $category_sp = Category::where('type', 'performer')
            ->where('key', 'sobstvennaja-produkcija')
            ->first();

        $category_sss = Category::where('type', 'performer')
            ->where('key', 'socialnyje-seti-i-sajty')
            ->first();

        $category_meroprijatija = Category::where('type', 'performer')
            ->where('key', 'meroprijatija')
            ->first();

        FrontVariablesLang::whereIn('key', [
            'price_' . $category_sz->id,
            'price_hint_' . $category_sz->id,
            'ready_for_political_advertising_' . $category_sz->id,
            'ready_for_political_advertising_hint_' . $category_sz->id,
            'hashtags_' . $category_sz->id,
            'hashtags_hint_' . $category_sz->id,
            'reach_audience_' . $category_sz->id,
            'reach_audience_hint_' . $category_sz->id,
            'payment_' . $category_sz->id,
            'payment_hint_' . $category_sz->id,
            'travel_abroad_' . $category_sz->id,
            'travel_abroad_hint_' . $category_sz->id,
            'photo_report_' . $category_sz->id,
            'photo_report_hint_' . $category_sz->id,
            'make_and_place_advertising_' . $category_sz->id,
            'make_and_place_advertising_hint_' . $category_sz->id,
            'negotiable_' . $category_sz->id,
            'negotiable_hint_' . $category_sz->id,
            'bargaining_' . $category_sz->id,
            'bargaining_hint_' . $category_sz->id,
            'description_' . $category_sz->id,
            'description_hint_' . $category_sz->id,
            'amount_' . $category_sz->id,
            'amount_hint_' . $category_sz->id,

            'price_' . $category_mototransport->id,
            'price_hint_' . $category_mototransport->id,
            'ready_for_political_advertising_' . $category_mototransport->id,
            'ready_for_political_advertising_hint_' . $category_mototransport->id,
            'hashtags_' . $category_mototransport->id,
            'hashtags_hint_' . $category_mototransport->id,
            'reach_audience_' . $category_mototransport->id,
            'reach_audience_hint_' . $category_mototransport->id,
            'payment_' . $category_mototransport->id,
            'payment_hint_' . $category_mototransport->id,
            'travel_abroad_' . $category_mototransport->id,
            'travel_abroad_hint_' . $category_mototransport->id,
            'photo_report_' . $category_mototransport->id,
            'photo_report_hint_' . $category_mototransport->id,
            'make_and_place_advertising_' . $category_mototransport->id,
            'make_and_place_advertising_hint_' . $category_mototransport->id,
            'negotiable_' . $category_mototransport->id,
            'negotiable_hint_' . $category_mototransport->id,
            'bargaining_' . $category_mototransport->id,
            'bargaining_hint_' . $category_mototransport->id,
            'description_' . $category_mototransport->id,
            'description_hint_' . $category_mototransport->id,
            'amount_' . $category_mototransport->id,
            'amount_hint_' . $category_mototransport->id,
            'length_' . $category_mototransport->id,
            'length_hint_' . $category_mototransport->id,
            'width_' . $category_mototransport->id,
            'width_hint_' . $category_mototransport->id,

            'price_' . $category_brk->id,
            'price_hint_' . $category_brk->id,
            'ready_for_political_advertising_' . $category_brk->id,
            'ready_for_political_advertising_hint_' . $category_brk->id,
            'hashtags_' . $category_brk->id,
            'hashtags_hint_' . $category_brk->id,
            'reach_audience_' . $category_brk->id,
            'reach_audience_hint_' . $category_brk->id,
            'payment_' . $category_brk->id,
            'payment_hint_' . $category_brk->id,
            'travel_abroad_' . $category_brk->id,
            'travel_abroad_hint_' . $category_brk->id,
            'photo_report_' . $category_brk->id,
            'photo_report_hint_' . $category_brk->id,
            'make_and_place_advertising_' . $category_brk->id,
            'make_and_place_advertising_hint_' . $category_brk->id,
            'negotiable_' . $category_brk->id,
            'negotiable_hint_' . $category_brk->id,
            'bargaining_' . $category_brk->id,
            'bargaining_hint_' . $category_brk->id,
            'description_' . $category_brk->id,
            'description_hint_' . $category_brk->id,
            'amount_' . $category_brk->id,
            'amount_hint_' . $category_brk->id,
            'length_' . $category_brk->id,
            'length_hint_' . $category_brk->id,
            'width_' . $category_brk->id,
            'width_hint_' . $category_brk->id,

            'price_' . $category_sp->id,
            'price_hint_' . $category_sp->id,
            'ready_for_political_advertising_' . $category_sp->id,
            'ready_for_political_advertising_hint_' . $category_sp->id,
            'hashtags_' . $category_sp->id,
            'hashtags_hint_' . $category_sp->id,
            'reach_audience_' . $category_sp->id,
            'reach_audience_hint_' . $category_sp->id,
            'payment_' . $category_sp->id,
            'payment_hint_' . $category_sp->id,
            'travel_abroad_' . $category_sp->id,
            'travel_abroad_hint_' . $category_sp->id,
            'photo_report_' . $category_sp->id,
            'photo_report_hint_' . $category_sp->id,
            'make_and_place_advertising_' . $category_sp->id,
            'make_and_place_advertising_hint_' . $category_sp->id,
            'negotiable_' . $category_sp->id,
            'negotiable_hint_' . $category_sp->id,
            'bargaining_' . $category_sp->id,
            'bargaining_hint_' . $category_sp->id,
            'description_' . $category_sp->id,
            'description_hint_' . $category_sp->id,
            'amount_' . $category_sp->id,
            'amount_hint_' . $category_sp->id,
            'length_' . $category_sp->id,
            'length_hint_' . $category_sp->id,
            'width_' . $category_sp->id,
            'width_hint_' . $category_sp->id,

            'price_' . $category_sss->id,
            'price_hint_' . $category_sss->id,
            'ready_for_political_advertising_' . $category_sss->id,
            'ready_for_political_advertising_hint_' . $category_sss->id,
            'hashtags_' . $category_sss->id,
            'hashtags_hint_' . $category_sss->id,
            'reach_audience_' . $category_sss->id,
            'reach_audience_hint_' . $category_sss->id,
            'payment_' . $category_sss->id,
            'payment_hint_' . $category_sss->id,
            'negotiable_' . $category_sss->id,
            'negotiable_hint_' . $category_sss->id,
            'bargaining_' . $category_sss->id,
            'bargaining_hint_' . $category_sss->id,
            'description_' . $category_sss->id,
            'description_hint_' . $category_sss->id,
            'attendance_' . $category_sss->id,
            'attendance_hint_' . $category_sss->id,
            'link_page_' . $category_sss->id,
            'link_page_hint_' . $category_sss->id,
            'link_page_' . $category_sss->id,
            'link_page_hint_' . $category_sss->id,

            'price_' . $category_meroprijatija->id,
            'price_hint_' . $category_meroprijatija->id,
            'ready_for_political_advertising_' . $category_meroprijatija->id,
            'ready_for_political_advertising_hint_' . $category_meroprijatija->id,
            'hashtags_' . $category_meroprijatija->id,
            'hashtags_hint_' . $category_meroprijatija->id,
            'reach_audience_' . $category_meroprijatija->id,
            'reach_audience_hint_' . $category_meroprijatija->id,
            'payment_' . $category_meroprijatija->id,
            'payment_hint_' . $category_meroprijatija->id,
            'travel_abroad_' . $category_meroprijatija->id,
            'travel_abroad_hint_' . $category_meroprijatija->id,
            'photo_report_' . $category_meroprijatija->id,
            'photo_report_hint_' . $category_meroprijatija->id,
            'make_and_place_advertising_' . $category_meroprijatija->id,
            'make_and_place_advertising_hint_' . $category_meroprijatija->id,
            'negotiable_' . $category_meroprijatija->id,
            'negotiable_hint_' . $category_meroprijatija->id,
            'bargaining_' . $category_meroprijatija->id,
            'bargaining_hint_' . $category_meroprijatija->id,
            'description_' . $category_meroprijatija->id,
            'description_hint_' . $category_meroprijatija->id,
            'amount_' . $category_meroprijatija->id,
            'amount_hint_' . $category_meroprijatija->id,
            'length_' . $category_meroprijatija->id,
            'length_hint_' . $category_meroprijatija->id,
            'width_' . $category_meroprijatija->id,
            'width_hint_' . $category_meroprijatija->id,
            'date_of_the_' . $category_meroprijatija->id,
            'date_of_the_' . $category_meroprijatija->id,
            'date_start_' . $category_meroprijatija->id,
            'date_start_hint_' . $category_meroprijatija->id,
            'date_finish_' . $category_meroprijatija->id,
            'date_finish_hint_' . $category_meroprijatija->id,
        ])->delete();

        $category_gruzoviki = Category::where('type', 'performer')
            ->where('key', 'gruzoviki')
            ->first();

        ChildCategory::where('category_id', $category_gruzoviki->id)->get()
            ->each(function ($childCategory) use ($category_gruzoviki) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'price_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'hashtags_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'payment_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'travel_abroad_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'travel_abroad_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'photo_report_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'negotiable_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'bargaining_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'description_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'description_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'length_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'length_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'width_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'width_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'amount_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'amount_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_avtotransport = Category::where('type', 'performer')
            ->where('key', 'avtotransport')
            ->first();

        ChildCategory::where('category_id', $category_avtotransport->id)->get()
            ->each(function ($childCategory) use ($category_gruzoviki) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'price_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'hashtags_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'payment_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'travel_abroad_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'travel_abroad_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'photo_report_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'negotiable_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'bargaining_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'description_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'description_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'length_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'length_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'width_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'width_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'amount_' . $category_gruzoviki->id . '_' . $childCategory->id,
                    'amount_hint_' . $category_gruzoviki->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_vt = Category::where('type', 'performer')
            ->where('key', 'vodnaja-tehnika')
            ->first();

        ChildCategory::where('category_id', $category_vt->id)->get()
            ->each(function ($childCategory) use ($category_vt) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_vt->id . '_' . $childCategory->id,
                    'price_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_vt->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'hashtags_' . $category_vt->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_vt->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'payment_' . $category_vt->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'travel_abroad_' . $category_vt->id . '_' . $childCategory->id,
                    'travel_abroad_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'photo_report_' . $category_vt->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_vt->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'negotiable_' . $category_vt->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'bargaining_' . $category_vt->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'description_' . $category_vt->id . '_' . $childCategory->id,
                    'description_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'length_' . $category_vt->id . '_' . $childCategory->id,
                    'length_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'width_' . $category_vt->id . '_' . $childCategory->id,
                    'width_hint_' . $category_vt->id . '_' . $childCategory->id,
                    'amount_' . $category_vt->id . '_' . $childCategory->id,
                    'amount_hint_' . $category_vt->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_la = Category::where('type', 'performer')
            ->where('key', 'letatelnyje-apparaty')
            ->first();

        ChildCategory::where('category_id', $category_la->id)->get()
            ->each(function ($childCategory) use ($category_la) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_la->id . '_' . $childCategory->id,
                    'price_hint_' . $category_la->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_la->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_la->id . '_' . $childCategory->id,
                    'hashtags_' . $category_la->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_la->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_la->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_la->id . '_' . $childCategory->id,
                    'payment_' . $category_la->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_la->id . '_' . $childCategory->id,
                    'travel_abroad_' . $category_la->id . '_' . $childCategory->id,
                    'travel_abroad_hint_' . $category_la->id . '_' . $childCategory->id,
                    'photo_report_' . $category_la->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_la->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_la->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_la->id . '_' . $childCategory->id,
                    'negotiable_' . $category_la->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_la->id . '_' . $childCategory->id,
                    'bargaining_' . $category_la->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_la->id . '_' . $childCategory->id,
                    'description_' . $category_la->id . '_' . $childCategory->id,
                    'description_hint_' . $category_la->id . '_' . $childCategory->id,
                    'length_' . $category_la->id . '_' . $childCategory->id,
                    'length_hint_' . $category_la->id . '_' . $childCategory->id,
                    'width_' . $category_la->id . '_' . $childCategory->id,
                    'width_hint_' . $category_la->id . '_' . $childCategory->id,
                    'amount_' . $category_la->id . '_' . $childCategory->id,
                    'amount_hint_' . $category_la->id . '_' . $childCategory->id,
                ])->delete();
            });


        $category_dze = Category::where('type', 'performer')
            ->where('key', 'doma-i-zdanija-eksterjer')
            ->first();

        ChildCategory::where('category_id', $category_dze->id)->get()
            ->each(function ($childCategory) use ($category_dze) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_dze->id . '_' . $childCategory->id,
                    'price_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_dze->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'hashtags_' . $category_dze->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_dze->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'payment_' . $category_dze->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'photo_report_' . $category_dze->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_dze->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'negotiable_' . $category_dze->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'bargaining_' . $category_dze->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'description_' . $category_dze->id . '_' . $childCategory->id,
                    'description_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'length_' . $category_dze->id . '_' . $childCategory->id,
                    'length_hint_' . $category_dze->id . '_' . $childCategory->id,
                    'width_' . $category_dze->id . '_' . $childCategory->id,
                    'width_hint_' . $category_dze->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_pi = Category::where('type', 'performer')
            ->where('key', 'pomeshhenija-interjer')
            ->first();

        ChildCategory::where('category_id', $category_pi->id)->get()
            ->each(function ($childCategory) use ($category_pi) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_pi->id . '_' . $childCategory->id,
                    'price_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_pi->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'hashtags_' . $category_pi->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_pi->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'payment_' . $category_pi->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'photo_report_' . $category_pi->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_pi->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'negotiable_' . $category_pi->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'bargaining_' . $category_pi->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'description_' . $category_pi->id . '_' . $childCategory->id,
                    'description_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'length_' . $category_pi->id . '_' . $childCategory->id,
                    'length_hint_' . $category_pi->id . '_' . $childCategory->id,
                    'width_' . $category_pi->id . '_' . $childCategory->id,
                    'width_hint_' . $category_pi->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_landshaft = Category::where('type', 'performer')
            ->where('key', 'landshaft')
            ->first();

        $amount = FormField::where('key', 'amount')->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category_landshaft->id, $amount->id]);

        ChildCategory::where('category_id', $category_landshaft->id)->get()
            ->each(function ($childCategory) use ($category_landshaft) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_landshaft->id . '_' . $childCategory->id,
                    'price_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_landshaft->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'hashtags_' . $category_landshaft->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_landshaft->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'payment_' . $category_landshaft->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'photo_report_' . $category_landshaft->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_landshaft->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'negotiable_' . $category_landshaft->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'bargaining_' . $category_landshaft->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'description_' . $category_landshaft->id . '_' . $childCategory->id,
                    'description_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'length_' . $category_landshaft->id . '_' . $childCategory->id,
                    'length_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                    'width_' . $category_landshaft->id . '_' . $childCategory->id,
                    'width_hint_' . $category_landshaft->id . '_' . $childCategory->id,
                ])->delete();
            });

        $category_mediaprojekty = Category::where('type', 'performer')
            ->where('key', 'mediaprojekty')
            ->first();

        ChildCategory::where('category_id', $category_mediaprojekty->id)->get()
            ->each(function ($childCategory) use ($category_mediaprojekty) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'price_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'hashtags_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'payment_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'photo_report_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'negotiable_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'bargaining_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'description_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'description_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'deadline_date_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'deadline_date_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'sample_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'sample_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'video_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                    'video_hint_' . $category_mediaprojekty->id . '_' . $childCategory->id,
                ])->delete();
            });

    }


}
