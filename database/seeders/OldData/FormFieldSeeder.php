<?php
namespace Database\Seeders\OldData;

use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormField::create(['name' => 'Цена', 'key' => 'price']);
        FrontVariablesLang::firstOrCreate(['key' => 'price', 'value' => 'Цена']);

        FormField::create(['name' => 'Оплата', 'key' => 'payment']);
        FrontVariablesLang::firstOrCreate(['key' => 'payment', 'value' => 'Оплата']);

        FormField::create(['name' => 'Хештеги', 'key' => 'hashtags']);
        FrontVariablesLang::firstOrCreate(['key' => 'hashtags', 'value' => 'Хештеги']);

        FormField::create(['name' => 'Охват аудитории', 'key' => 'reach_audience']);
        FrontVariablesLang::firstOrCreate(['key' => 'reach_audience', 'value' => 'Охват аудитории']);

        FormField::create(['name' => 'Выезд за границу', 'key' => 'travel_abroad']);
        FrontVariablesLang::firstOrCreate(['key' => 'travel_abroad', 'value' => 'Выезд за границу']);

        FormField::create(['name' => 'Готовы к политической рекламе', 'key' => 'ready_for_political_advertising']);
        FrontVariablesLang::firstOrCreate(['key' => 'ready_for_political_advertising', 'value' => 'Готовы к политической рекламе']);

        FormField::create(['name' => 'Фотоотчет', 'key' => 'photo_report']);
        FrontVariablesLang::firstOrCreate(['key' => 'photo_report', 'value' => 'Фотоотчет']);

        FormField::create(['name' => 'Сами изготовим и разместим рекламу', 'key' => 'make_and_place_advertising']);
        FrontVariablesLang::firstOrCreate(['key' => 'make_and_place_advertising', 'value' => 'Сами изготовим и разместим рекламу']);

        FormField::create(['name' => 'Количество', 'key' => 'amount']);
        FrontVariablesLang::firstOrCreate(['key' => 'amount', 'value' => 'Количество']);

        FormField::create(['name' => 'Длина', 'key' => 'length']);
        FrontVariablesLang::firstOrCreate(['key' => 'length', 'value' => 'Длина']);

        FormField::create(['name' => 'Ширина', 'key' => 'width']);
        FrontVariablesLang::firstOrCreate(['key' => 'width', 'value' => 'Ширина']);

        FormField::create(['name' => 'Видео', 'key' => 'video']);
        FrontVariablesLang::firstOrCreate(['key' => 'video', 'value' => 'Видео']);

        FormField::create(['name' => 'Дата дедлайна', 'key' => 'deadline_date']);
        FrontVariablesLang::firstOrCreate(['key' => 'deadline_date', 'value' => 'Дата дедлайна']);

        FormField::create(['name' => 'Сэмпл', 'key' => 'sample']);
        FrontVariablesLang::firstOrCreate(['key' => 'sample', 'value' => 'Сэмпл']);

        FormField::create(['name' => 'Ссылка на страницу', 'key' => 'link_page']);
        FrontVariablesLang::firstOrCreate(['key' => 'link_page', 'value' => 'Ссылка на страницу']);

        FormField::create(['name' => 'Посещаемость', 'key' => 'attendance']);
        FrontVariablesLang::firstOrCreate(['key' => 'attendance', 'value' => 'Посещаемость']);

        FormField::create(['name' => 'Дата проведения', 'key' => 'date_of_the']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_of_the', 'value' => 'Дата проведения']);

        FormField::create(['name' => 'Дата начала', 'key' => 'date_start']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_start', 'value' => 'Дата начала']);

        FormField::create(['name' => 'Дата окончания', 'key' => 'date_finish']);
        FrontVariablesLang::firstOrCreate(['key' => 'date_finish', 'value' => 'Дата окончания']);

    }
}
