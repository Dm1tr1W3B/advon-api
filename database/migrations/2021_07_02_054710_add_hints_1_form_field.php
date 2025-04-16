<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHints1FormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $category_puteshestvija = Category::where('type', 'performer')
            ->where('key', 'puteshestvija')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_puteshestvija->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_puteshestvija->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_puteshestvija->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_puteshestvija->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_puteshestvija->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_puteshestvija->id, 'value' => 'Сколько людей смогут увидеть рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_puteshestvija->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_puteshestvija->id, 'value' => 'Поедите ли вы за границу']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_puteshestvija->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_puteshestvija->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_puteshestvija->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_puteshestvija->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_puteshestvija->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_puteshestvija->id, 'value' => 'Опишите подробно где вы побываете, сколько дней, какую рекламу хотели бы привлечь.']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_puteshestvija->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_puteshestvija->id , 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_puteshestvija->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_puteshestvija->id, 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_start_' . $category_puteshestvija->id, 'value' => 'Дата начала']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_start_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_' . $category_puteshestvija->id, 'value' => 'Дата окончания']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_hint_' . $category_puteshestvija->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_of_the_' . $category_puteshestvija->id, 'value' => 'Дата поездки']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_of_the_hint_' . $category_puteshestvija->id, 'value' => '']);

        $category_pp = Category::where('type', 'performer')
            ->where('key', 'pechatnaja-produkcija')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_pp->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_pp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_pp->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_pp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_pp->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_pp->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_pp->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_pp->id, 'value' => 'Сколько людей смогут видеть эту рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_pp->id, 'value' => 'Сами изготовите рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_pp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_pp->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_pp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_pp->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_pp->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_pp->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_pp->id, 'value' => 'Опишите подробно, где и как разместить рекламу.']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_pp->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_pp->id , 'value' => 'мм']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_pp->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_pp->id, 'value' => 'мм']);

        $category_oe = Category::where('type', 'performer')
            ->where('key', 'odezhda-ekipirovka')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_oe->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_oe->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_oe->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_oe->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_oe->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_oe->id, 'value' => 'Сколько людей смогут видеть эту рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_oe->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_oe->id, 'value' => 'Выезжает ли команда за границу']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_oe->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_oe->id, 'value' => 'Сами изготовите и разместите рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_oe->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_oe->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_oe->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_oe->id, 'value' => 'Опишите подробно ваши планы на строительство или места для рекламы в уже строящихся объектах.']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_start_' . $category_oe->id, 'value' => 'Дата начала']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_start_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_' . $category_oe->id, 'value' => 'Дата окончания']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_finish_hint_' . $category_oe->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_oe->id, 'value' => 'Количество экипировки']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_oe->id, 'value' => '']);

        $category_ot = Category::where('type', 'performer')
            ->where('key', 'obshhestvennyj-transport')
            ->first();

        $childCategorys_ot = ChildCategory::where('category_id', $category_ot->id)->get();

        $childCategorys_ot->each(function ($childCategory) use ($category_ot) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту рекламу в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Вылетает ли транспорт за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Опишите ваше средство, его маршрут, какова его привлекательность для рекламодателей.']);

            FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'см']);
        });

        $childCategory = $childCategorys_ot->where('key', 'avtobusy')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество автобусов под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'trollejbusy')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество троллейбусов под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'tramvai')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество трамваев под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'metro')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество вагонов под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'elektrichki-pojezda')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество вагонов под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'pojezda')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество вагонов под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'marshrutnyje-taksi')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество такси под рекламу']);

        $childCategory = $childCategorys_ot->where('key', 'drugoje')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество ....']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_ot->id . '_' . $childCategory->id, 'value' => 'Количество транспорта под рекламу']);

        $category_stritart = Category::where('type', 'performer')
            ->where('key', 'stritart')
            ->first();

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_stritart->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_stritart->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_stritart->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_stritart->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_stritart->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_stritart->id, 'value' => 'Сколько людей с могут увидеть рекламу?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_stritart->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_stritart->id, 'value' => 'Предоставлю фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_stritart->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_stritart->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_stritart->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_stritart->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_stritart->id, 'value' => 'Опишите расположение места, его видимость прохожими и рекламную привлекательность для рекламодателя.']);

        FrontVariablesLang::firstOrCreate(['key' => 'length_' . $category_stritart->id, 'value' => 'Длина']);
        FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_stritart->id , 'value' => 'см']);

        FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_stritart->id, 'value' => 'Ширина']);
        FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_stritart->id, 'value' => 'см']);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $category_puteshestvija = Category::where('type', 'performer')
            ->where('key', 'puteshestvija')
            ->first();

        $category_pp = Category::where('type', 'performer')
            ->where('key', 'pechatnaja-produkcija')
            ->first();

        $category_oe = Category::where('type', 'performer')
            ->where('key', 'odezhda-ekipirovka')
            ->first();

        $category_stritart = Category::where('type', 'performer')
            ->where('key', 'stritart')
            ->first();


        FrontVariablesLang::whereIn('key', [ 'price_' . $category_puteshestvija->id,
            'price_hint_' . $category_puteshestvija->id,
            'ready_for_political_advertising_' . $category_puteshestvija->id,
            'ready_for_political_advertising_hint_' . $category_puteshestvija->id,
            'hashtags_' . $category_puteshestvija->id,
            'hashtags_hint_' . $category_puteshestvija->id,
            'reach_audience_' . $category_puteshestvija->id,
            'reach_audience_hint_' . $category_puteshestvija->id,
            'payment_' . $category_puteshestvija->id,
            'payment_hint_' . $category_puteshestvija->id,
            'travel_abroad_' . $category_puteshestvija->id,
            'travel_abroad_hint_' . $category_puteshestvija->id,
            'photo_report_' . $category_puteshestvija->id,
            'photo_report_hint_' . $category_puteshestvija->id,
            'make_and_place_advertising_' . $category_puteshestvija->id,
            'make_and_place_advertising_hint_' . $category_puteshestvija->id,
            'negotiable_' . $category_puteshestvija->id,
            'negotiable_hint_' . $category_puteshestvija->id,
            'bargaining_' . $category_puteshestvija->id,
            'bargaining_hint_' . $category_puteshestvija->id,
            'description_' . $category_puteshestvija->id,
            'description_hint_' . $category_puteshestvija->id,
            'amount_' . $category_puteshestvija->id,
            'amount_hint_' . $category_puteshestvija->id,
            'length_' . $category_puteshestvija->id,
            'length_hint_' . $category_puteshestvija->id,
            'width_' . $category_puteshestvija->id,
            'width_hint_' . $category_puteshestvija->id,
            'date_of_the_' . $category_puteshestvija->id,
            'date_of_the_' . $category_puteshestvija->id,
            'date_start_' . $category_puteshestvija->id,
            'date_start_hint_' . $category_puteshestvija->id,
            'date_finish_' . $category_puteshestvija->id,
            'date_finish_hint_' . $category_puteshestvija->id,

            'price_hint_' . $category_pp->id,
            'ready_for_political_advertising_' . $category_pp->id,
            'ready_for_political_advertising_hint_' . $category_pp->id,
            'hashtags_' . $category_pp->id,
            'hashtags_hint_' . $category_pp->id,
            'reach_audience_' . $category_pp->id,
            'reach_audience_hint_' . $category_pp->id,
            'payment_' . $category_pp->id,
            'payment_hint_' . $category_pp->id,
            'travel_abroad_' . $category_pp->id,
            'travel_abroad_hint_' . $category_pp->id,
            'photo_report_' . $category_pp->id,
            'photo_report_hint_' . $category_pp->id,
            'make_and_place_advertising_' . $category_pp->id,
            'make_and_place_advertising_hint_' . $category_pp->id,
            'negotiable_' . $category_pp->id,
            'negotiable_hint_' . $category_pp->id,
            'bargaining_' . $category_pp->id,
            'bargaining_hint_' . $category_pp->id,
            'description_' . $category_pp->id,
            'description_hint_' . $category_pp->id,
            'amount_' . $category_pp->id,
            'amount_hint_' . $category_pp->id,
            'length_' . $category_pp->id,
            'length_hint_' . $category_pp->id,
            'width_' . $category_pp->id,
            'width_hint_' . $category_pp->id,

            'price_hint_' . $category_oe->id,
            'ready_for_political_advertising_' . $category_oe->id,
            'ready_for_political_advertising_hint_' . $category_oe->id,
            'hashtags_' . $category_oe->id,
            'hashtags_hint_' . $category_oe->id,
            'reach_audience_' . $category_oe->id,
            'reach_audience_hint_' . $category_oe->id,
            'payment_' . $category_oe->id,
            'payment_hint_' . $category_oe->id,
            'travel_abroad_' . $category_oe->id,
            'travel_abroad_hint_' . $category_oe->id,
            'photo_report_' . $category_oe->id,
            'photo_report_hint_' . $category_oe->id,
            'make_and_place_advertising_' . $category_oe->id,
            'make_and_place_advertising_hint_' . $category_oe->id,
            'negotiable_' . $category_oe->id,
            'negotiable_hint_' . $category_oe->id,
            'bargaining_' . $category_oe->id,
            'bargaining_hint_' . $category_oe->id,
            'description_' . $category_oe->id,
            'description_hint_' . $category_oe->id,
            'amount_' . $category_oe->id,
            'amount_hint_' . $category_oe->id,
            'length_' . $category_oe->id,
            'length_hint_' . $category_oe->id,
            'width_' . $category_oe->id,
            'width_hint_' . $category_oe->id,
            'date_of_the_' . $category_oe->id,
            'date_of_the_' . $category_oe->id,
            'date_start_' . $category_oe->id,
            'date_start_hint_' . $category_oe->id,
            'date_finish_' . $category_oe->id,
            'date_finish_hint_' . $category_oe->id,

            'price_hint_' . $category_stritart->id,
            'ready_for_political_advertising_' . $category_stritart->id,
            'ready_for_political_advertising_hint_' . $category_stritart->id,
            'hashtags_' . $category_stritart->id,
            'hashtags_hint_' . $category_stritart->id,
            'reach_audience_' . $category_stritart->id,
            'reach_audience_hint_' . $category_stritart->id,
            'payment_' . $category_stritart->id,
            'payment_hint_' . $category_stritart->id,
            'travel_abroad_' . $category_stritart->id,
            'travel_abroad_hint_' . $category_stritart->id,
            'photo_report_' . $category_stritart->id,
            'photo_report_hint_' . $category_stritart->id,
            'make_and_place_advertising_' . $category_stritart->id,
            'make_and_place_advertising_hint_' . $category_stritart->id,
            'negotiable_' . $category_stritart->id,
            'negotiable_hint_' . $category_stritart->id,
            'bargaining_' . $category_stritart->id,
            'bargaining_hint_' . $category_stritart->id,
            'description_' . $category_stritart->id,
            'description_hint_' . $category_stritart->id,
            'amount_' . $category_stritart->id,
            'amount_hint_' . $category_stritart->id,
            'length_' . $category_stritart->id,
            'length_hint_' . $category_stritart->id,
            'width_' . $category_stritart->id,
            'width_hint_' . $category_stritart->id,



        ])->delete();


        $category_ot = Category::where('type', 'performer')
            ->where('key', 'obshhestvennyj-transport')
            ->first();

        ChildCategory::where('category_id', $category_ot->id)->get()
            ->each(function ($childCategory) use ($category_ot) {
                FrontVariablesLang::whereIn('key', [
                    'price_' . $category_ot->id . '_' . $childCategory->id,
                    'price_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_' . $category_ot->id . '_' . $childCategory->id,
                    'ready_for_political_advertising_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'hashtags_' . $category_ot->id . '_' . $childCategory->id,
                    'hashtags_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'reach_audience_' . $category_ot->id . '_' . $childCategory->id,
                    'reach_audience_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'payment_' . $category_ot->id . '_' . $childCategory->id,
                    'payment_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'photo_report_' . $category_ot->id . '_' . $childCategory->id,
                    'photo_report_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'make_and_place_advertising_' . $category_ot->id . '_' . $childCategory->id,
                    'make_and_place_advertising_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'negotiable_' . $category_ot->id . '_' . $childCategory->id,
                    'negotiable_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'bargaining_' . $category_ot->id . '_' . $childCategory->id,
                    'bargaining_hint_' . $category_ot->id . '_' . $childCategory->id,
                    'description_' . $category_ot->id . '_' . $childCategory->id,
                    'description_hint_' . $category_ot->id . '_' . $childCategory->id
                ])->delete();
            });


    }
}
