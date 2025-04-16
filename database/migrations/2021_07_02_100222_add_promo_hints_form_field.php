<?php

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoHintsFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categores = Category::where('type', 'employer')
            ->whereIn('key', [
                'telo',
                'strizhka-zhivotnyh',
                'mototransport',
                'sobstvennaja-produkcija',
                'bilbordy-reklamnyje-konstrukcii',
                'pechatnaja-produkcija',
                'socialnyje-seti-i-sajty',
                'meroprijatija',
                'puteshestvija',
                'odezhda-ekipirovka',
                'nestandartnoje-mesto',
                'stroitelstvo',
                'stritart'

            ])
            ->get();

        $categores->each(function ($category) {
            FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category->id, 'value' => 'Цена']);
            FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category->id, 'value' => 'Политическая реклама?']);
            FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category->id, 'value' => '']);

            if (!in_array($category->key, [
                'sobstvennaja-produkcija',
                'bilbordy-reklamnyje-konstrukcii',
                'pechatnaja-produkcija',
                'socialnyje-seti-i-sajty',
                'puteshestvija',
                'stritart'

            ])) {
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id, 'value' => 'Сами изготовим и разместим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id, 'value' => '']);
            }

            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category->id, 'value' => 'Договорная']);
            FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category->id, 'value' => 'Торг']);
            FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category->id, 'value' => '']);

            if ($category->key != 'meroprijatija')
                FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category->id, 'value' => '']);

            FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category->id, 'value' => 'Описание']);

            if ($category->key == 'telo') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество исполнителей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, на какой срок и тд.']);
            }

            if ($category->key == 'strizhka-zhivotnyh') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество животных']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, какие животные нужны, на какой срок и тд.']);
            }

            if ($category->key == 'mototransport') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество мототранспорта']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей промокампании.']);
            }

            if ($category->key == 'bilbordy-reklamnyje-konstrukcii') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество рекламных поверхностей:']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'ННапишите подробнее о вашей рекламе, на какой срок и тд.']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id, 'value' => 'Сами изготовим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id, 'value' => '']);
            }

            if ($category->key == 'pechatnaja-produkcija') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество рекламных полей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей рекламе, на какой срок и тд.']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id, 'value' => 'Сами изготовим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id, 'value' => '']);
            }

            if ($category->key == 'socialnyje-seti-i-sajty') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество исполнителей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, на какой срок и тд.']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id, 'value' => 'Сами изготовим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id, 'value' => '']);
            }

            if ($category->key == 'meroprijatija') {

                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции,что на мероприятии надо будет рекламировать и тд.']);
            }

            if ($category->key == 'puteshestvija') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество путешественников']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, что в путешествии надо будет рекламировать, в какой срок и тд.']);

                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id, 'value' => 'Сами изготовим рекламу']);
                FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id, 'value' => '']);
            }

            if ($category->key == 'odezhda-ekipirovka') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество исполнителей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, какой вид спорта интересен, на какой срок и тд.']);
            }

            if ($category->key == 'onestandartnoje-mesto') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество исполнителей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, что ищете, на какой срок и тд.']);
            }

            if ($category->key == 'stroitelstvo') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'Требуемое количество исполнителей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей акции, на какой срок и тд.']);
            }

            if ($category->key == 'stritart') {
                FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id, 'value' => 'рекламных поверхностей']);
                FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id, 'value' => 'Напишите подробнее о вашей промокампании']);
            }

        });


        $categores = Category::where('type', 'employer')
            ->whereIn('key', [
                'lichnyje-veshhi',
                'avtotransport',
                'gruzoviki',
                'vodnaja-tehnika',
                'letatelnyje-apparaty',
                'landshaft',
                'obshhestvennyj-transport',
                'mediaprojekty'
            ])
            ->get();

        $categores->each(function ($category) {

            ChildCategory::where('category_id', $category->id)->get()
                ->each(function ($childCategory) use ($category) {
                    FrontVariablesLang::firstOrCreate(['key' => 'price_' . $category->id . '_' . $childCategory->id, 'value' => 'Цена']);
                    FrontVariablesLang::firstOrCreate(['key' => 'price_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                    FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_' . $category->id . '_' . $childCategory->id, 'value' => 'Политическая реклама?']);
                    FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                    if ($category->key != 'mediaprojekty') {
                        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id . '_' . $childCategory->id, 'value' => 'Сами изготовим и разместим рекламу']);
                        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category->id . '_' . $childCategory->id, 'value' => 'Описание']);
                        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id . '_' . $childCategory->id, 'value' => 'Напишите подробнее о вашей промокампании.']);
                    }

                    FrontVariablesLang::firstOrCreate(['key' => 'negotiable_' . $category->id . '_' . $childCategory->id, 'value' => 'Договорная']);
                    FrontVariablesLang::firstOrCreate(['key' => 'negotiable_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                    FrontVariablesLang::firstOrCreate(['key' => 'bargaining_' . $category->id . '_' . $childCategory->id, 'value' => 'Торг']);
                    FrontVariablesLang::firstOrCreate(['key' => 'bargaining_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);


                    if ($category->key == 'lichnyje-veshhi')
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество исполнителей']);

                    if ($category->key == 'avtotransport')
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество автотранспорта']);

                    if ($category->key == 'gruzoviki')
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество грузовиков']);

                    if ($category->key == 'vodnaja-tehnika')
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество водной техники']);

                    if ($category->key == 'letatelnyje-apparaty')
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество летательных аппаратов']);

                    if (in_array($category->key, [
                            'doma-i-zdanija-eksterjer',
                            'pomeshhenija-interjer',
                            'landshaft',
                        ]
                    ))
                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество рекламных поверхностей']);

                    if ($category->key == 'obshhestvennyj-transport') {

                        if ($childCategory->key == 'avtobusy')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество автобусов']);

                        if ($childCategory->key == 'trollejbusy')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество троллейбусов']);

                        if ($childCategory->key == 'tramvai')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество трамваев']);

                        if ($childCategory->key == 'pojezda' || $childCategory->key == 'metro' || $childCategory->key == 'elektrichki-pojezda')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество вагонов']);

                        if ($childCategory->key == 'marshrutnyje-taksi')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество такси']);

                        if ($childCategory->key == 'drugoje')
                            FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество транспорта']);
                    }

                    if ($category->key == 'mediaprojekty') {
                        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_' . $category->id . '_' . $childCategory->id, 'value' => 'Сами изготовим рекламу']);
                        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                        FrontVariablesLang::firstOrCreate(['key' => 'amount_' . $category->id . '_' . $childCategory->id, 'value' => 'Требуемое количество медиапроектов']);

                        FrontVariablesLang::firstOrCreate(['key' => 'description_' . $category->id . '_' . $childCategory->id, 'value' => 'Описание']);
                        FrontVariablesLang::firstOrCreate(['key' => 'description_hint_' . $category->id . '_' . $childCategory->id, 'value' => 'Расскажите подробнее какова должна быть интеграция вашей рекламы в медиапроект.']);

                        FrontVariablesLang::firstOrCreate(['key' => 'sample' . $category->id . '_' . $childCategory->id, 'value' => 'Сэмпл']);
                        FrontVariablesLang::firstOrCreate(['key' => 'sample_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);
                    }

                    FrontVariablesLang::firstOrCreate(['key' => 'amount_hint_' . $category->id . '_' . $childCategory->id, 'value' => '']);

                });

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Category::where('type', 'employer')
            ->whereIn('key', [
                'telo',
                'strizhka-zhivotnyh',
                'mototransport',
                'sobstvennaja-produkcija',
                'bilbordy-reklamnyje-konstrukcii',
                'pechatnaja-produkcija',
                'socialnyje-seti-i-sajty',
                'meroprijatija',
                'puteshestvija',
                'odezhda-ekipirovka',
                'nestandartnoje-mesto',
                'stroitelstvo',
                'stritart'

            ])
            ->get()
            ->each(function ($category) {

                FrontVariablesLang::whereIn('key', [
                    'price_' . $category->id,
                    'price_hint_' . $category->id,
                    'ready_for_political_advertising_' . $category->id,
                    'ready_for_political_advertising_hint_' . $category->id,
                    'make_and_place_advertising_' . $category->id,
                    'make_and_place_advertising_hint_' . $category->id,
                    'negotiable_' . $category->id,
                    'negotiable_hint_' . $category->id,
                    'bargaining_' . $category->id,
                    'bargaining_hint_' . $category->id,
                    'description_' . $category->id,
                    'description_hint_' . $category->id,
                    'amount_' . $category->id,
                    'amount_hint_' . $category->id,
                ])->delete();
            });

        $categores = Category::where('type', 'employer')
            ->whereIn('key', [
                'lichnyje-veshhi',
                'avtotransport',
                'gruzoviki',
                'vodnaja-tehnika',
                'letatelnyje-apparaty',
                'landshaft',
                'obshhestvennyj-transport',
                'mediaprojekty'
            ])
            ->get();

        $categores->each(function ($category) {

            ChildCategory::where('category_id', $category->id)->get()
                ->each(function ($childCategory) use ($category) {

                    FrontVariablesLang::whereIn('key', [
                        'price_' . $category->id . '_' . $childCategory->id,
                        'price_hint_' . $category->id . '_' . $childCategory->id,
                        'ready_for_political_advertising_' . $category->id . '_' . $childCategory->id,
                        'ready_for_political_advertising_hint_' . $category->id . '_' . $childCategory->id,
                        'make_and_place_advertising_' . $category->id . '_' . $childCategory->id,
                        'make_and_place_advertising_hint_' . $category->id . '_' . $childCategory->id,
                        'negotiable_' . $category->id . '_' . $childCategory->id,
                        'negotiable_hint_' . $category->id . '_' . $childCategory->id,
                        'bargaining_' . $category->id . '_' . $childCategory->id,
                        'bargaining_hint_' . $category->id . '_' . $childCategory->id,
                        'description_' . $category->id . '_' . $childCategory->id,
                        'description_hint_' . $category->id . '_' . $childCategory->id,
                        'amount_' . $category->id . '_' . $childCategory->id,
                        'amount_hint_' . $category->id . '_' . $childCategory->id,
                    ])->delete();

                });
        });


    }
}
