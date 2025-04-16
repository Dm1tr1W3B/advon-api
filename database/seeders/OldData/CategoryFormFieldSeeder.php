<?php
namespace Database\Seeders\OldData;


use App\Models\Category;
use App\Models\FormField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryFormFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::get();


        // цена
        $price = FormField::where('key','price')->first();
        // Готовы к политической рекламе
        $readyForPoliticalAdvertising = FormField::where('key','ready_for_political_advertising')->first();

        $categories->each(function ($category) use ($price, $readyForPoliticalAdvertising) {
            DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $price->id]);
            DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $readyForPoliticalAdvertising->id]);
        });

        // Хештеги
        $hashtag = FormField::where('key','hashtags')->first();
        $categories->where('type', 'performer')
            ->each(function ($category) use ($hashtag) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $hashtag->id]);
            });

        // Охват аудитории
        $reachAudience = FormField::where('key','reach_audience')->first();
        $categories->where('type', 'performer')
            ->where('id', '!=', 53) // Социальные сети
            ->each(function ($category) use ($reachAudience) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $reachAudience->id]);
            });

        // Оплата
        $payment = FormField::where('key','payment')->first();
        $categories->where('type', 'performer')
            ->where('id', '!=', 147) // Печатная продукция
            ->each(function ($category) use ($payment) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $payment->id]);
            });

        // Выезд за границу
        $travelAbroad = FormField::where('key','travel_abroad')->first();
        $categories->where('type', 'performer')
            ->where('id', '!=', 31) // Дома и здания, экстерьер
            ->where('id', '!=', 36) // Помещения, интерьер
            ->where('id', '!=', 41) // На своей продукции
            ->where('id', '!=', 42) // Ландшафт
            ->where('id', '!=', 45) // Билборды
            ->where('id', '!=', 46) // Медиапроекты
            ->where('id', '!=', 53) // Социальные сети
            ->where('id', '!=', 54) // Мероприятия
            ->where('id', '!=', 147) // Печатная продукция
            ->where('id', '!=', 57) // Нестандартное место
            ->where('id', '!=', 58) // Строительство
            ->where('id', '!=', 62) // Общественный транспорт
            ->where('id', '!=', 149) // Стрит-арт
            ->each(function ($category) use ($travelAbroad) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $travelAbroad->id]);
            });

        // Фотоотчет
        $photoReport = FormField::where('key','photo_report')->first();
        $categories->where('type', 'performer')
            ->where('id', '!=', 46) // Медиапроекты
            ->where('id', '!=', 53) // Социальные сети
            ->where('id', '!=', 54) // Мероприятия
            ->where('id', '!=', 147) // Печатная продукция
            ->each(function ($category) use ($photoReport) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $photoReport->id]);
            });

        // Сами изготовим и разместим рекламу
        $makeAndPlaceAdvertising = FormField::where('key','make_and_place_advertising')->first();
        $categories
            ->where('id', '!=', 41) // На своей продукции
            ->where('id', '!=', 53) // Социальные сети
            ->where('id', '!=', 149) // Стрит-арт
            ->where('id', '!=', 110) // На своей продукции промо
            ->where('id', '!=', 150) // Стрит-арт промо
            ->each(function ($category) use ($makeAndPlaceAdvertising) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $makeAndPlaceAdvertising->id]);
            });

        // Количество
        $amount = FormField::where('key','amount')->first();
        $categories
            ->where('id', '!=', 59) // Тело
            ->where('id', '!=', 31) // Дома и здания, экстерьер
            ->where('id', '!=', 36) // Помещения, интерьер
            ->where('id', '!=', 42) // Ландшафт
            ->where('id', '!=', 46) // Медиапроекты
            ->where('id', '!=', 53) // Социальные сети
            ->where('id', '!=', 55) // Путешествия
            ->where('id', '!=', 147) // Печатная продукция
            ->where('id', '!=', 149) // Стрит-арт
            ->where('id', '!=', 124) // Мероприятия промо
            ->each(function ($category) use ($amount) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $amount->id]);
            });

        // Размеры
        $length = FormField::where('key','length')->first();
        $width = FormField::where('key','width')->first();
        $categories->where('type', 'performer')
            ->where('id', '!=', 59) // Тело
            ->where('id', '!=', 6) // Животные
            ->where('id', '!=', 46) // Медиапроекты
            ->where('id', '!=', 53) // Социальные сети
            ->where('id', '!=', 56) // Спортивная экипировка
            ->each(function ($category) use ($length, $width) {
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $length->id]);
                DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$category->id, $width->id]);
            });

        $video = FormField::where('key','video')->first();
        $deadlineDate = FormField::where('key','deadline_date')->first();
        $sample = FormField::where('key','sample')->first();
        $categoryMedia = $categories->where('id', 46) // Медиапроекты
            ->first();
        $categoryMediaP = $categories->where('id', 115) // Медиапроекты промо
        ->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryMedia->id, $video->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryMedia->id, $deadlineDate->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryMedia->id, $sample->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryMediaP->id, $sample->id]);

        $linkPage = FormField::where('key','link_page')->first();
        $attendance = FormField::where('key','attendance')->first();
        $categoryCC = $categories->where('id', 53) // Социальные сети
            ->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryCC->id, $linkPage->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryCC->id, $attendance->id]);

        $dateOfThe = FormField::where('key','date_of_the')->first();
        $categoryM = $categories->where('id', 54) // Мероприятия
        ->first();
        $categoryT = $categories->where('id', 55) // Путешествия
        ->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryM->id, $dateOfThe->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryT->id, $dateOfThe->id]);

        $dateStart = FormField::where('key','date_start')->first();
        $dateFinish = FormField::where('key','date_finish')->first();
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryM->id, $dateStart->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryM->id, $dateFinish->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryT->id, $dateStart->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [$categoryT->id, $dateFinish->id]);
        // Спортивная экипировка
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [56, $dateStart->id]);
        DB::insert('insert into category_form_field (category_id, form_field_id) values (?, ?)', [56, $dateFinish->id]);
    }
}
