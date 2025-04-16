<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHintsFormFieldForTeloLichnyjeVeshhi extends Migration
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

        FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_telo->id, 'value' => 'Цена']);
        FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_telo->id, 'value' => 'Готовы к политической рекламе']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_telo->id, 'value' => 'Хештеги']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_telo->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_telo->id, 'value' => 'Охват']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_telo->id, 'value' => 'Сколько людей смогут увидеть эту область тела в указанный период?']);

        FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_telo->id, 'value' => 'Оплата']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_telo->id, 'value' => 'Выезд за границу']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_telo->id, 'value' => 'Фотоотчет']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_telo->id, 'value' => 'Сами изготовим и разместим рекламу']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_telo->id, 'value' => 'Договорная']);
        FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_telo->id, 'value' => 'Торг']);
        FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_telo->id, 'value' => '']);

        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_telo->id, 'value' => 'Описание']);
        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_telo->id, 'value' => 'Напишите, как часто выбранная область тела будет на виду. Опишите рекламную привлекательность рекламодателям']);

        $category_lichnyjeVeshhi = Category::where('type', 'performer')
            ->where('key', 'lichnyje-veshhi')
            ->first();

        $childCategorys_lv = ChildCategory::where('category_id', $category_lichnyjeVeshhi->id)->get();

        $childCategorys_lv->each(function ($childCategory) use ($category_lichnyjeVeshhi) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Готовы к политической рекламе']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Хештеги']);
            FrontVariablesLang::firstOrCreate(['key' => 'hashtags_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Хештеги (Введите названия компаний, чьё внимание вы хотели бы обратить на это объявление, через запятую, без “#”)']);

            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Охват']);
            FrontVariablesLang::firstOrCreate(['key' => 'reach_audience_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Сколько людей смогут увидеть эту вещь в указанный период?']);

            FrontVariablesLang::firstOrCreate(['key' => 'payment_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Оплата']);
            FrontVariablesLang::firstOrCreate(['key' => 'payment_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Выезд за границу']);
            FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Фотоотчет']);
            FrontVariablesLang::firstOrCreate(['key' => 'photo_report_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
            FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Описание']);
            FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Опишите: как часто вы пользуетесь этим предметом в общественном месте, рекламную привлекательность для рекламодателей']);

            FrontVariablesLang::firstOrCreate(['key' => 'length' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Длина']);
            FrontVariablesLang::firstOrCreate(['key' => 'length_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'см']);

            FrontVariablesLang::firstOrCreate(['key' => 'width_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Ширина']);
            FrontVariablesLang::firstOrCreate(['key' => 'width_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'см']);

        });

        $childCategory = $childCategorys_lv->where('key', 'odezhda')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество одежды под рекламу']);

        $childCategory = $childCategorys_lv->where('key', 'kompjutery')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество компьютеров под рекламу']);

        $childCategory = $childCategorys_lv->where('key', 'telefony')->first();

        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество телефонов под рекламу']);

        $childCategory = $childCategorys_lv->where('key', 'drugije-veshhi')->first();
        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id, 'value' => 'Количество вещей под рекламу']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $category = Category::where('type', 'performer')
            ->where('key', 'telo')
            ->first();

        FrontVariablesLang::whereIn('key', [
            'price_' . $category->id,
            'price_hint_' . $category->id,
            'ready_for_political_advertising_' . $category->id,
            'ready_for_political_advertising_hint_' . $category->id,
            'hashtags_' . $category->id,
            'hashtags_hint_' . $category->id,
            'reach_audience_' . $category->id,
            'reach_audience_hint_' . $category->id,
            'payment_' . $category->id,
            'payment_hint_' . $category->id,
            'travel_abroad_' . $category->id,
            'travel_abroad_hint_' . $category->id,
            'photo_report_' . $category->id,
            'photo_report_hint_' . $category->id,
            'make_and_place_advertising_' . $category->id,
            'make_and_place_advertising_hint_' . $category->id,
            'negotiable_' . $category->id,
            'negotiable_hint_' . $category->id,
            'bargaining_' . $category->id,
            'bargaining_hint_' . $category->id,
            'description_' . $category->id,
            'description_hint_' . $category->id,
        ])->delete();

        $category_lichnyjeVeshhi = Category::where('type', 'performer')
            ->where('key', 'lichnyje-veshhi')
            ->first();

        $childCategorys_lv = ChildCategory::where('category_id', $category_lichnyjeVeshhi->id)->get();

        $childCategorys_lv->each(function ($childCategory) use ($category_lichnyjeVeshhi) {
            FrontVariablesLang::whereIn('key', [
                'price_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'price_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'ready_for_political_advertising_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'ready_for_political_advertising_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'hashtags_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'hashtags_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'reach_audience_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'reach_audience_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'payment_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'payment_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'travel_abroad_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'travel_abroad_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'photo_report_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'photo_report_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'make_and_place_advertising_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'make_and_place_advertising_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'negotiable_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'negotiable_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'bargaining_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'bargaining_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'description_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'description_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'length_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'length_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'width_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'width_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'amount_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
                'amount_hint_' . $category_lichnyjeVeshhi->id . '_' . $childCategory->id,
            ])->delete();
        });
    }
}
