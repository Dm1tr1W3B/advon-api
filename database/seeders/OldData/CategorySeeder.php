<?php
namespace Database\Seeders\OldData;


use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['id' => 59, 'name' => 'Тело', 'key' => 'telo', 'type' => 'performer', 'num_left' => 134 , 'num_right' => 135, 'title_ograph' => 'Рекламируй бренды на себе!', 'keyword' => 'telo']);
        FrontVariablesLang::firstOrCreate(['key' => 'telo', 'value' => 'Тело']);

        Category::create(['id' => 6, 'name' => 'Животные', 'key' => 'strizhka-zhivotnyh', 'type' => 'performer', 'num_left' => 136 , 'num_right' => 137, 'title_ograph' => 'Твоё животное заработает денег!', 'keyword' => 'strizhka-zhivotnyh']);
        FrontVariablesLang::firstOrCreate(['key' => 'strizhka-zhivotnyh', 'value' => 'Животные']);

        $category = Category::create(['id' => 7, 'name' => 'Личные вещи', 'key' => 'lichnyje-veshhi', 'type' => 'performer', 'num_left' => 2 , 'num_right' => 11, 'title_ograph' => 'Размести рекламу на своих вещах!', 'keyword' => 'lichnyje-veshhi']);
        FrontVariablesLang::firstOrCreate(['key' => 'lichnyje-veshhi', 'value' => 'Личные вещи']);
        ChildCategory::create(['id' => 10, 'category_id' => $category->id, 'name' => 'Другие вещи', 'key' => 'drugije-veshhi', 'num_left' => 9 , 'num_right' => 10, 'keyword' => 'lichnyje-veshhi/drugije-veshhi',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugije-veshhi', 'value' => 'Другие вещи']);
        ChildCategory::create(['id' => 8, 'category_id' => $category->id, 'name' => 'Компьютеры', 'key' => 'kompjutery', 'num_left' => 5 , 'num_right' => 6, 'keyword' => 'lichnyje-veshhi/kompjutery',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kompjutery', 'value' => 'Компьютеры']);
        ChildCategory::create(['category_id' => $category->id, 'name' => 'Одежда', 'key' => 'odezhda', 'num_left' => 3 , 'num_right' => 4, 'keyword' => 'lichnyje-veshhi/odezhda',]);
        FrontVariablesLang::firstOrCreate(['key' => 'odezhda', 'value' => 'Одежда']);
        ChildCategory::create(['id' => 9, 'category_id' => $category->id, 'name' => 'Телефоны', 'key' => 'telefony', 'num_left' => 7 , 'num_right' => 8, 'keyword' => 'lichnyje-veshhi/telefony',]);
        FrontVariablesLang::firstOrCreate(['key' => 'telefony', 'value' => 'Телефоны']);

        $category = Category::create(['id' => 11, 'name' => 'Автотранспорт', 'key' => 'avtotransport', 'type' => 'performer', 'num_left' => 12 , 'num_right' => 19, 'title_ograph' => 'Забрендируй свою машину!', 'keyword' => 'avtotransport']);
        FrontVariablesLang::firstOrCreate(['key' => 'avtotransport', 'value' => 'Автотранспорт']);
        ChildCategory::create(['id' => 14, 'category_id' => $category->id, 'name' => 'Другое место', 'key' => 'drugoje-mesto', 'num_left' => 17 , 'num_right' => 18, 'keyword' => 'avtotransport/drugoje-mesto',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugoje-mesto', 'value' => 'Другое место']);
        ChildCategory::create(['id' => 13, 'category_id' => $category->id, 'name' => 'Кузов', 'key' => 'kuzov', 'num_left' => 15 , 'num_right' => 16, 'keyword' => 'avtotransport/kuzov',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kuzov', 'value' => 'Кузов']);
        ChildCategory::create(['id' => 12, 'category_id' => $category->id, 'name' => 'Стекло', 'key' => 'steklo', 'num_left' => 13 , 'num_right' => 14, 'keyword' => 'avtotransport/steklo',]);
        FrontVariablesLang::firstOrCreate(['key' => 'steklo', 'value' => 'Стекло']);

        $category = Category::create(['id' => 15, 'name' => 'Мототранспорт', 'key' => 'mototransport', 'type' => 'performer', 'num_left' => 28 , 'num_right' => 29, 'title_ograph' => 'Забрендируй свой байк!', 'keyword' => 'mototransport']);
        FrontVariablesLang::firstOrCreate(['key' => 'mototransport', 'value' => 'Мототранспорт']);

        $category = Category::create(['id' => 16, 'name' => 'Грузовики', 'key' => 'gruzoviki', 'type' => 'performer', 'num_left' => 20 , 'num_right' => 27, 'title_ograph' => 'Забрендируй свой грузовик!', 'keyword' => 'gruzoviki']);
        FrontVariablesLang::firstOrCreate(['key' => 'gruzoviki', 'value' => 'Грузовики']);
        ChildCategory::create(['id' => 19, 'category_id' => $category->id, 'name' => 'Другое место', 'key' => 'drugoje-mesto', 'num_left' => 25 , 'num_right' => 26, 'keyword' => 'gruzoviki/drugoje-mesto',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugoje-mesto', 'value' => 'Другое место']);
        ChildCategory::create(['id' => 17, 'category_id' => $category->id, 'name' => 'Кузов', 'key' => 'kuzov', 'num_left' => 21 , 'num_right' => 22, 'keyword' => 'gruzoviki/kuzov',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kuzov', 'value' => 'Кузов']);
        ChildCategory::create(['id' => 18, 'category_id' => $category->id, 'name' => 'Прицеп', 'key' => 'pricep', 'num_left' => 23 , 'num_right' => 24, 'keyword' => 'gruzoviki/pricep',]);
        FrontVariablesLang::firstOrCreate(['key' => 'pricep', 'value' => 'Прицеп']);

        $category = Category::create(['id' => 20, 'name' => 'Водная техника', 'key' => 'vodnaja-tehnika', 'type' => 'performer', 'num_left' => 48 , 'num_right' => 59, 'title_ograph' => 'Забрендируй свой катер!', 'keyword' => 'vodnaja-tehnika']);
        FrontVariablesLang::firstOrCreate(['key' => 'vodnaja-tehnika', 'value' => 'Водная техника']);
        ChildCategory::create(['id' => 25, 'category_id' => $category->id, 'name' => 'Другая техника', 'key' => 'drugaja-tehnika', 'num_left' => 57 , 'num_right' => 58, 'keyword' => 'vodnaja-tehnika/drugaja-tehnika',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugaja-tehnika', 'value' => 'Другая техника']);
        ChildCategory::create(['id' => 22, 'category_id' => $category->id, 'name' => 'Яхты', 'key' => 'jahta', 'num_left' => 51 , 'num_right' => 52, 'keyword' => 'vodnaja-tehnika/jahta',]);
        FrontVariablesLang::firstOrCreate(['key' => 'jahta', 'value' => 'Яхты']);
        ChildCategory::create(['id' => 21, 'category_id' => $category->id, 'name' => 'Корабли', 'key' => 'korabl', 'num_left' => 49 , 'num_right' => 50, 'keyword' => 'vodnaja-tehnika/korabl',]);
        FrontVariablesLang::firstOrCreate(['key' => 'korabl', 'value' => 'Корабли']);
        ChildCategory::create(['id' => 24, 'category_id' => $category->id, 'name' => 'Лодки', 'key' => 'lodka', 'num_left' => 55 , 'num_right' => 56, 'keyword' => 'vodnaja-tehnika/lodka',]);
        FrontVariablesLang::firstOrCreate(['key' => 'lodka', 'value' => 'Лодки']);
        ChildCategory::create(['id' => 23, 'category_id' => $category->id, 'name' => 'Маломерные катера', 'key' => 'malomernyj-kater', 'num_left' => 53 , 'num_right' => 54, 'keyword' => 'vodnaja-tehnika/malomernyj-kater',]);
        FrontVariablesLang::firstOrCreate(['key' => 'malomernyj-kater', 'value' => 'Маломерные катера']);

        $category = Category::create(['id' => 26, 'name' => 'Летательные аппараты', 'key' => 'letatelnyje-apparaty', 'type' => 'performer', 'num_left' => 60 , 'num_right' => 69, 'title_ograph' => 'Забрендируй свой самолёт!', 'keyword' => 'letatelnyje-apparaty']);
        FrontVariablesLang::firstOrCreate(['key' => 'letatelnyje-apparaty', 'value' => 'Летательные аппараты']);
        ChildCategory::create(['id' => 30, 'category_id' => $category->id, 'name' => 'Другие', 'key' => 'drugije', 'num_left' => 67 , 'num_right' => 68, 'keyword' => 'letatelnyje-apparaty/drugije',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugije', 'value' => 'Другие']);
        ChildCategory::create(['id' => 29, 'category_id' => $category->id, 'name' => 'Парапланы / Дельтапланы', 'key' => 'paraplany-deltaplany', 'num_left' => 65 , 'num_right' => 66, 'keyword' => 'letatelnyje-apparaty/paraplany-deltaplany',]);
        FrontVariablesLang::firstOrCreate(['key' => 'paraplany-deltaplany', 'value' => 'Парапланы / Дельтапланы']);
        ChildCategory::create(['id' => 27, 'category_id' => $category->id, 'name' => 'Самолеты', 'key' => 'samolety', 'num_left' => 61 , 'num_right' => 62, 'keyword' => 'letatelnyje-apparaty/samolety',]);
        FrontVariablesLang::firstOrCreate(['key' => 'samolety', 'value' => 'Самолеты']);
        ChildCategory::create(['id' => 28, 'category_id' => $category->id, 'name' => 'Вертолеты', 'key' => 'vertolety', 'num_left' => 63 , 'num_right' => 64, 'keyword' => 'letatelnyje-apparaty/vertolety',]);
        FrontVariablesLang::firstOrCreate(['key' => 'vertolety', 'value' => 'Вертолеты']);

        $category = Category::create(['id' => 31, 'name' => 'Дома и здания, экстерьер', 'key' => 'doma-i-zdanija-eksterjer', 'type' => 'performer', 'num_left' => 70 , 'num_right' => 83, 'title_ograph' => 'Размести рекламу на фасаде!', 'keyword' => 'doma-i-zdanija-eksterjer']);
        FrontVariablesLang::firstOrCreate(['key' => 'doma-i-zdanija-eksterjer', 'value' => 'Дома и здания, экстерьер']);
        ChildCategory::create(['id' => 140, 'category_id' => $category->id, 'name' => 'Аэропорт', 'key' => 'aeroport', 'num_left' => 79 , 'num_right' => 80, 'keyword' => 'doma-i-zdanija-eksterjer/aeroport',]);
        FrontVariablesLang::firstOrCreate(['key' => 'aeroport', 'value' => 'Аэропорт']);
        ChildCategory::create(['id' => 35, 'category_id' => $category->id, 'name' => 'Иное', 'key' => 'inoje', 'num_left' => 81 , 'num_right' => 82, 'keyword' => 'doma-i-zdanija-eksterjer/inoje',]);
        FrontVariablesLang::firstOrCreate(['key' => 'inoje', 'value' => 'Иное']);
        ChildCategory::create(['id' => 34, 'category_id' => $category->id, 'name' => 'Кафе, рестораны', 'key' => 'kafe-restorany', 'num_left' => 75 , 'num_right' => 76, 'keyword' => 'doma-i-zdanija-eksterjer/kafe-restorany',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kafe-restorany', 'value' => 'Кафе, рестораны']);
        ChildCategory::create(['id' => 33, 'category_id' => $category->id, 'name' => 'Нежилые здания', 'key' => 'nezhiloje-zdanije', 'num_left' => 73 , 'num_right' => 74, 'keyword' => 'doma-i-zdanija-eksterjer/nezhiloje-zdanije',]);
        FrontVariablesLang::firstOrCreate(['key' => 'nezhiloje-zdanije', 'value' => 'Нежилые здания']);
        ChildCategory::create(['id' => 139, 'category_id' => $category->id, 'name' => 'Вокзал', 'key' => 'vokzal', 'num_left' => 77 , 'num_right' => 78, 'keyword' => 'doma-i-zdanija-eksterjer/vokzal',]);
        FrontVariablesLang::firstOrCreate(['key' => 'vokzal', 'value' => 'Вокзал']);
        ChildCategory::create(['id' => 32, 'category_id' => $category->id, 'name' => 'Жилые дома', 'key' => 'zhiloj-dom', 'num_left' => 71 , 'num_right' => 72, 'keyword' => 'doma-i-zdanija-eksterjer/zhiloj-dom',]);
        FrontVariablesLang::firstOrCreate(['key' => 'zhiloj-dom', 'value' => 'Жилые дома']);

        $category = Category::create(['id' => 36, 'name' => 'Помещения, интерьер', 'key' => 'pomeshhenija-interjer', 'type' => 'performer', 'num_left' => 84 , 'num_right' => 97, 'title_ograph' => 'Маркетплейс в вашем учреждении!', 'keyword' => 'pomeshhenija-interjer']);
        FrontVariablesLang::firstOrCreate(['key' => 'pomeshhenija-interjer', 'value' => 'Помещения, интерьер']);
        ChildCategory::create(['id' => 142, 'category_id' => $category->id, 'name' => 'Аэропорт', 'key' => 'aeroport', 'num_left' => 93 , 'num_right' => 94, 'keyword' => 'pomeshhenija-interjer/aeroport',]);
        FrontVariablesLang::firstOrCreate(['key' => 'aeroport', 'value' => 'Аэропорт']);
        ChildCategory::create(['id' => 40, 'category_id' => $category->id, 'name' => 'Иное', 'key' => 'inoje', 'num_left' => 95 , 'num_right' => 96, 'keyword' => 'pomeshhenija-interjer/inoje',]);
        FrontVariablesLang::firstOrCreate(['key' => 'inoje', 'value' => 'Иное']);
        ChildCategory::create(['id' => 39, 'category_id' => $category->id, 'name' => 'Кафе, рестораны', 'key' => 'kafe-restorany', 'num_left' => 89 , 'num_right' => 90, 'keyword' => 'pomeshhenija-interjer/kafe-restorany',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kafe-restorany', 'value' => 'Кафе, рестораны']);
        ChildCategory::create(['id' => 38, 'category_id' => $category->id, 'name' => 'Нежилые здания', 'key' => 'nezhilyje-zdanija', 'num_left' => 87 , 'num_right' => 88, 'keyword' => 'pomeshhenija-interjer/nezhilyje-zdanija',]);
        FrontVariablesLang::firstOrCreate(['key' => 'nezhilyje-zdanija', 'value' => 'Нежилые здания']);
        ChildCategory::create(['id' => 141, 'category_id' => $category->id, 'name' => 'Вокзал', 'key' => 'vokzal', 'num_left' => 91 , 'num_right' => 92, 'keyword' => 'pomeshhenija-interjer/vokzal',]);
        FrontVariablesLang::firstOrCreate(['key' => 'vokzal', 'value' => 'Вокзал']);
        ChildCategory::create(['id' => 37, 'category_id' => $category->id, 'name' => 'Жилые дома', 'key' => 'zhilyje-doma', 'num_left' => 85 , 'num_right' => 86, 'keyword' => 'pomeshhenija-interjer/zhilyje-doma',]);
        FrontVariablesLang::firstOrCreate(['key' => 'zhilyje-doma', 'value' => 'Жилые дома']);

        $category = Category::create(['id' => 41, 'name' => 'На своей продукции', 'key' => 'sobstvennaja-produkcija', 'type' => 'performer', 'num_left' => 126 , 'num_right' => 127, 'title_ograph' => 'Заработай на рекламе на своей продукции!', 'keyword' => 'sobstvennaja-produkcija']);
        FrontVariablesLang::firstOrCreate(['key' => 'sobstvennaja-produkcija', 'value' => 'На своей продукции']);

        $category = Category::create(['id' => 42, 'name' => 'Ландшафт', 'key' => 'landshaft', 'type' => 'performer', 'num_left' => 116 , 'num_right' => 121, 'title_ograph' => 'Реклама в дизайне ландшафта!', 'keyword' => 'landshaft']);
        FrontVariablesLang::firstOrCreate(['key' => 'landshaft', 'value' => 'Ландшафт']);
        ChildCategory::create(['id' => 43, 'category_id' => $category->id, 'name' => 'Личное пространство', 'key' => 'lichnoje-prostranstvo', 'num_left' => 117 , 'num_right' => 118, 'keyword' => 'landshaft/lichnoje-prostranstvo',]);
        FrontVariablesLang::firstOrCreate(['key' => 'lichnoje-prostranstvo', 'value' => 'Личное пространство']);
        ChildCategory::create(['id' => 44, 'category_id' => $category->id, 'name' => 'Общественное пространство', 'key' => 'obshhestvennoje-prostranstvo', 'num_left' => 119 , 'num_right' => 120, 'keyword' => 'landshaft/obshhestvennoje-prostranstvo',]);
        FrontVariablesLang::firstOrCreate(['key' => 'obshhestvennoje-prostranstvo', 'value' => 'Общественное пространство']);

        $category = Category::create(['id' => 45, 'name' => 'Билборды / рекламные конструкции', 'key' => 'bilbordy-reklamnyje-konstrukcii', 'type' => 'performer', 'num_left' => 98 , 'num_right' => 99, 'title_ograph' => 'Размести рекламу на своём билборде!', 'keyword' => 'bilbordy-reklamnyje-konstrukcii']);
        FrontVariablesLang::firstOrCreate(['key' => 'bilbordy-reklamnyje-konstrukcii', 'value' => 'Билборды / рекламные конструкции']);

        $category = Category::create(['id' => 46, 'name' => 'Медиапроекты', 'key' => 'mediaprojekty', 'type' => 'performer', 'num_left' => 100 , 'num_right' => 115, 'title_ograph' => 'Найди спонсора для своего медиапроекта!', 'keyword' => 'mediaprojekty']);
        FrontVariablesLang::firstOrCreate(['key' => 'mediaprojekty', 'value' => 'Медиапроекты']);
        ChildCategory::create(['id' => 52, 'category_id' => $category->id, 'name' => 'Другие проекты', 'key' => 'drugije-projekty', 'num_left' => 111 , 'num_right' => 112, 'keyword' => 'mediaprojekty/drugije-projekty',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugije-projekty', 'value' => 'Другие проекты']);
        ChildCategory::create(['id' => 49, 'category_id' => $category->id, 'name' => 'Кино', 'key' => 'kino', 'num_left' => 105 , 'num_right' => 106, 'keyword' => 'mediaprojekty/kino',]);
        FrontVariablesLang::firstOrCreate(['key' => 'kino', 'value' => 'Кино']);
        ChildCategory::create(['id' => 51, 'category_id' => $category->id, 'name' => 'Музыка', 'key' => 'muzyka', 'num_left' => 109 , 'num_right' => 110, 'keyword' => 'mediaprojekty/muzyka',]);
        FrontVariablesLang::firstOrCreate(['key' => 'muzyka', 'value' => 'Музыка']);
        ChildCategory::create(['id' => 60, 'category_id' => $category->id, 'name' => 'Промокампании', 'key' => 'promokampanii', 'num_left' => 113 , 'num_right' => 114, 'keyword' => 'mediaprojekty/promokampanii',]);
        FrontVariablesLang::firstOrCreate(['key' => 'promokampanii', 'value' => 'Промокампании']);
        ChildCategory::create(['id' => 48, 'category_id' => $category->id, 'name' => 'Радио', 'key' => 'radio', 'num_left' => 103 , 'num_right' => 104, 'keyword' => 'mediaprojekty/radio',]);
        FrontVariablesLang::firstOrCreate(['key' => 'radio', 'value' => 'Радио']);
        ChildCategory::create(['id' => 47, 'category_id' => $category->id, 'name' => 'Телепередачи', 'key' => 'teleperedacha', 'num_left' => 101 , 'num_right' => 102, 'keyword' => 'mediaprojekty/teleperedacha',]);
        FrontVariablesLang::firstOrCreate(['key' => 'teleperedacha', 'value' => 'Телепередачи']);
        ChildCategory::create(['id' => 50, 'category_id' => $category->id, 'name' => 'Видеоклипы', 'key' => 'videoklip', 'num_left' => 107 , 'num_right' => 108, 'keyword' => 'mediaprojekty/videoklip',]);
        FrontVariablesLang::firstOrCreate(['key' => 'videoklip', 'value' => 'Видеоклипы']);

        $category = Category::create(['id' => 53, 'name' => 'Социальные сети и сайты', 'key' => 'socialnyje-seti-i-sajty', 'type' => 'performer', 'num_left' => 132 , 'num_right' => 133, 'title_ograph' => 'Ищи рекламодателя для своих сайтов!', 'keyword' => 'socialnyje-seti-i-sajty']);
        FrontVariablesLang::firstOrCreate(['key' => 'socialnyje-seti-i-sajty', 'value' => 'Социальные сети и сайты']);

        $category = Category::create(['id' => 54, 'name' => 'Мероприятия', 'key' => 'meroprijatija', 'type' => 'performer', 'num_left' => 122 , 'num_right' => 123, 'title_ograph' => 'Найди спонсора твоего мероприятия!', 'keyword' => 'meroprijatija']);
        FrontVariablesLang::firstOrCreate(['key' => 'meroprijatija', 'value' => 'Мероприятия']);

        $category = Category::create(['id' => 55, 'name' => 'Путешествия', 'key' => 'puteshestvija', 'type' => 'performer', 'num_left' => 124 , 'num_right' => 125, 'title_ograph' => 'В дорогу? Заработай на рекламе!', 'keyword' => 'puteshestvija']);
        FrontVariablesLang::firstOrCreate(['key' => 'puteshestvija', 'value' => 'Путешествия']);

        $category = Category::create(['id' => 56, 'name' => 'Спортивная экипировка', 'key' => 'odezhda-ekipirovka', 'type' => 'performer', 'num_left' => 128 , 'num_right' => 129, 'title_ograph' => 'Найди спонсора для своей команды!', 'keyword' => 'odezhda-ekipirovka']);
        FrontVariablesLang::firstOrCreate(['key' => 'odezhda-ekipirovka', 'value' => 'Спортивная экипировка']);

        $category = Category::create(['id' => 57, 'name' => 'Нестандартное место', 'key' => 'nestandartnoje-mesto', 'type' => 'performer', 'num_left' => 138 , 'num_right' => 139, 'title_ograph' => 'Рекламируй и зарабатывай!', 'keyword' => 'nestandartnoje-mesto']);
        FrontVariablesLang::firstOrCreate(['key' => 'nestandartnoje-mesto', 'value' => 'Нестандартное место']);

        $category = Category::create(['id' => 58, 'name' => 'Строительство', 'key' => 'stroitelstvo', 'type' => 'performer', 'num_left' => 130 , 'num_right' => 131, 'title_ograph' => 'Найди инвестора под свой проект!', 'keyword' => 'stroitelstvo']);
        FrontVariablesLang::firstOrCreate(['key' => 'stroitelstvo', 'value' => 'Строительство']);


        $category = Category::create(['id' => 62, 'name' => 'Общественный транспорт', 'key' => 'obshhestvennyj-transport', 'type' => 'performer', 'num_left' => 30 , 'num_right' => 47, 'title_ograph' => 'Размещайте рекламу в общественном транспорте!', 'keyword' => 'obshhestvennyj-transport']);
        FrontVariablesLang::firstOrCreate(['key' => 'obshhestvennyj-transport', 'value' => 'Общественный транспорт']);
        ChildCategory::create(['id' => 63, 'category_id' => $category->id, 'name' => 'Автобусы', 'key' => 'avtobusy', 'num_left' => 31 , 'num_right' => 32, 'keyword' => 'obshhestvennyj-transport/avtobusy',]);
        FrontVariablesLang::firstOrCreate(['key' => 'avtobusy', 'value' => 'Автобусы']);
        ChildCategory::create(['id' => 68, 'category_id' => $category->id, 'name' => 'Другое', 'key' => 'drugoje', 'num_left' => 45 , 'num_right' => 46, 'keyword' => 'obshhestvennyj-transport/drugoje',]);
        FrontVariablesLang::firstOrCreate(['key' => 'drugoje', 'value' => 'Другое']);
        ChildCategory::create(['id' => 64, 'category_id' => $category->id, 'name' => 'Электрички', 'key' => 'elektrichki-pojezda', 'num_left' => 39 , 'num_right' => 40, 'keyword' => 'obshhestvennyj-transport/elektrichki-pojezda',]);
        FrontVariablesLang::firstOrCreate(['key' => 'elektrichki-pojezda', 'value' => 'Электрички']);
        ChildCategory::create(['id' => 67, 'category_id' => $category->id, 'name' => 'Маршрутные такси', 'key' => 'marshrutnyje-taksi', 'num_left' => 43 , 'num_right' => 44, 'keyword' => 'obshhestvennyj-transport/marshrutnyje-taksi',]);
        FrontVariablesLang::firstOrCreate(['key' => 'marshrutnyje-taksi', 'value' => 'Маршрутные такси']);
        ChildCategory::create(['id' => 70, 'category_id' => $category->id, 'name' => 'Метро', 'key' => 'metro', 'num_left' => 37 , 'num_right' => 38, 'keyword' => 'obshhestvennyj-transport/metro',]);
        FrontVariablesLang::firstOrCreate(['key' => 'metro', 'value' => 'Метро']);
        ChildCategory::create(['id' => 69, 'category_id' => $category->id, 'name' => 'Поезда', 'key' => 'pojezda', 'num_left' => 41 , 'num_right' => 42, 'keyword' => 'obshhestvennyj-transport/pojezda',]);
        FrontVariablesLang::firstOrCreate(['key' => 'pojezda', 'value' => 'Поезда']);
        ChildCategory::create(['id' => 65, 'category_id' => $category->id, 'name' => 'Трамваи', 'key' => 'tramvai', 'num_left' => 35 , 'num_right' => 36, 'keyword' => 'obshhestvennyj-transport/tramvai',]);
        FrontVariablesLang::firstOrCreate(['key' => 'tramvai', 'value' => 'Трамваи']);
        ChildCategory::create(['id' => 66, 'category_id' => $category->id, 'name' => 'Троллейбусы', 'key' => 'trollejbusy', 'num_left' => 33 , 'num_right' => 34, 'keyword' => 'obshhestvennyj-transport/trollejbusy',]);
        FrontVariablesLang::firstOrCreate(['key' => 'trollejbusy', 'value' => 'Троллейбусы']);

        $category = Category::create(['id' => 147, 'name' => 'Печатная продукция', 'key' => 'pechatnaja-produkcija', 'type' => 'performer', 'num_left' => 140 , 'num_right' => 141, 'title_ograph' => 'Рекламируйте своё дело в печатных изданиях!', 'keyword' => 'pechatnaja-produkcija']);
        FrontVariablesLang::firstOrCreate(['key' => 'pechatnaja-produkcija', 'value' => 'Печатная продукция']);

        $category = Category::create(['id' => 149, 'name' => 'Стрит-арт', 'key' => 'stritart', 'type' => 'performer', 'num_left' => 142 , 'num_right' => 143, 'title_ograph' => 'Разместите рекламу у уличных художников!', 'keyword' => 'stritart']);
        FrontVariablesLang::firstOrCreate(['key' => 'stritart', 'value' => 'Стрит-арт']);

        /////  promo ////


        Category::create(['id' => 129, 'name' => 'Тело промо', 'key' => 'telo', 'type' => 'employer', 'num_left' => 276 , 'num_right' => 277, 'title_ograph' => 'Рекламируй бренды на себе!', 'keyword' => 'telo-promo']);

        Category::create(['id' => 74, 'name' => 'Животные промо', 'key' => 'strizhka-zhivotnyh', 'type' => 'employer', 'num_left' => 278 , 'num_right' => 279, 'title_ograph' => 'Твоё животное заработает денег!', 'keyword' => 'strizhka-zhivotnyh-promo']);

        $category = Category::create(['id' => 75, 'name' => 'Личные вещи промо', 'key' => 'lichnyje-veshhi', 'type' => 'employer', 'num_left' => 144 , 'num_right' => 153, 'title_ograph' => 'Размести рекламу на своих вещах!', 'keyword' => 'lichnyje-veshhi-promo']);
        ChildCategory::create(['id' => 78, 'category_id' => $category->id, 'name' => 'Другие вещи промо', 'key' => 'drugije-veshhi', 'num_left' => 151 , 'num_right' => 152, 'keyword' => 'lichnyje-veshhi/drugije-veshhi',]);
        ChildCategory::create(['id' => 76, 'category_id' => $category->id, 'name' => 'Компьютеры промо', 'key' => 'kompjutery', 'num_left' => 147 , 'num_right' => 148, 'keyword' => 'lichnyje-veshhi-promo/kompjutery',]);
        ChildCategory::create(['id' => 79, 'category_id' => $category->id, 'name' => 'Одежда  промо', 'key' => 'odezhda', 'num_left' => 145 , 'num_right' => 146, 'keyword' => 'lichnyje-veshhi-promo/odezhda',]);
        ChildCategory::create(['id' => 77, 'category_id' => $category->id, 'name' => 'Телефоны промо', 'key' => 'telefony', 'num_left' => 149 , 'num_right' => 150, 'keyword' => 'lichnyje-veshhi-promo/telefony',]);

        $category = Category::create(['id' => 80, 'name' => 'Автотранспорт промо', 'key' => 'avtotransport', 'type' => 'employer', 'num_left' => 154 , 'num_right' => 161, 'title_ograph' => 'Забрендируй свою машину!', 'keyword' => 'avtotransport-promo']);
        ChildCategory::create(['id' => 83, 'category_id' => $category->id, 'name' => 'Другое место  промо', 'key' => 'drugoje-mesto', 'num_left' => 159 , 'num_right' => 160, 'keyword' => 'avtotransport-promo/drugoje-mesto',]);
        ChildCategory::create(['id' => 82, 'category_id' => $category->id, 'name' => 'Кузов  промо', 'key' => 'kuzov', 'num_left' => 157 , 'num_right' => 158, 'keyword' => 'avtotransport-promo/kuzov',]);
        ChildCategory::create(['id' => 81, 'category_id' => $category->id, 'name' => 'Стекло промо', 'key' => 'steklo', 'num_left' => 155 , 'num_right' => 156, 'keyword' => 'avtotransport-promo/steklo',]);

        Category::create(['id' => 84, 'name' => 'Мототранспорт промо', 'key' => 'mototransport', 'type' => 'employer', 'num_left' => 170 , 'num_right' => 171, 'title_ograph' => 'Забрендируй свой байк!', 'keyword' => 'mototransport-promo']);

        $category = Category::create(['id' => 85, 'name' => 'Грузовики промо', 'key' => 'gruzoviki', 'type' => 'employer', 'num_left' => 162 , 'num_right' => 169, 'title_ograph' => 'Забрендируй свой грузовик!', 'keyword' => 'gruzoviki-promo']);
        ChildCategory::create(['id' => 88, 'category_id' => $category->id, 'name' => 'Другое место промо', 'key' => 'drugoje-mesto', 'num_left' => 167 , 'num_right' => 168, 'keyword' => 'gruzoviki-promo/drugoje-mesto',]);
        ChildCategory::create(['id' => 86, 'category_id' => $category->id, 'name' => 'Кузов промо', 'key' => 'kuzov', 'num_left' => 163 , 'num_right' => 164, 'keyword' => 'gruzoviki-promo/kuzov',]);
        ChildCategory::create(['id' => 87, 'category_id' => $category->id, 'name' => 'Прицеп промо', 'key' => 'pricep', 'num_left' => 165 , 'num_right' => 166, 'keyword' => 'gruzoviki-promo/pricep',]);

        $category = Category::create(['id' => 89, 'name' => 'Водная техника промо', 'key' => 'vodnaja-tehnika', 'type' => 'employer', 'num_left' => 190 , 'num_right' => 201, 'title_ograph' => 'Забрендируй свой катер!', 'keyword' => 'vodnaja-tehnika-promo']);
        ChildCategory::create(['id' => 94, 'category_id' => $category->id, 'name' => 'Другая техника промо', 'key' => 'drugaja-tehnika', 'num_left' => 199 , 'num_right' => 200, 'keyword' => 'vodnaja-tehnika-promo/drugaja-tehnika',]);
        ChildCategory::create(['id' => 91, 'category_id' => $category->id, 'name' => 'Яхты  промо', 'key' => 'jahta', 'num_left' => 193 , 'num_right' => 194, 'keyword' => 'vodnaja-tehnika-promo/jahta',]);
        ChildCategory::create(['id' => 90, 'category_id' => $category->id, 'name' => 'Корабли  промо', 'key' => 'korabl', 'num_left' => 191 , 'num_right' => 192, 'keyword' => 'vodnaja-tehnika-promo/korabl',]);
        ChildCategory::create(['id' => 93, 'category_id' => $category->id, 'name' => 'Лодки промо', 'key' => 'lodka', 'num_left' => 197 , 'num_right' => 198, 'keyword' => 'vodnaja-tehnika-promo/lodka',]);
        ChildCategory::create(['id' => 92, 'category_id' => $category->id, 'name' => 'Маломерные катера  промо', 'key' => 'malomernyj-kater', 'num_left' => 195 , 'num_right' => 196, 'keyword' => 'vodnaja-tehnika-promo/malomernyj-kater',]);

        $category = Category::create(['id' => 95, 'name' => 'Летательные аппараты промо', 'key' => 'letatelnyje-apparaty', 'type' => 'employer', 'num_left' => 202 , 'num_right' => 211, 'title_ograph' => 'Забрендируй свой самолёт!', 'keyword' => 'letatelnyje-apparaty-promo']);
        ChildCategory::create(['id' => 99, 'category_id' => $category->id, 'name' => 'Другие  промо', 'key' => 'drugije', 'num_left' => 209 , 'num_right' => 210, 'keyword' => 'letatelnyje-apparaty-promo/drugije',]);
        ChildCategory::create(['id' => 98, 'category_id' => $category->id, 'name' => 'Парапланы / Дельтапланы  промо', 'key' => 'paraplany-deltaplany', 'num_left' => 207 , 'num_right' => 208, 'keyword' => 'letatelnyje-apparaty-promo/paraplany-deltaplany',]);
        ChildCategory::create(['id' => 96, 'category_id' => $category->id, 'name' => 'Самолеты промо', 'key' => 'samolety', 'num_left' => 203 , 'num_right' => 204, 'keyword' => 'letatelnyje-apparaty-promo/samolety',]);
        ChildCategory::create(['id' => 97, 'category_id' => $category->id, 'name' => 'Вертолеты  промо', 'key' => 'vertolety', 'num_left' => 205 , 'num_right' => 206, 'keyword' => 'letatelnyje-apparaty-promo/vertolety',]);

        $category = Category::create(['id' => 100, 'name' => 'Дома и здания, экстерьер промо', 'key' => 'doma-i-zdanija-eksterjer', 'type' => 'employer', 'num_left' => 212 , 'num_right' => 225, 'title_ograph' => 'Размести рекламу на фасаде!', 'keyword' => 'doma-i-zdanija-eksterjer-promo']);
        ChildCategory::create(['id' => 144, 'category_id' => $category->id, 'name' => 'Аэропорт промо', 'key' => 'aeroport', 'num_left' => 221 , 'num_right' => 222, 'keyword' => 'doma-i-zdanija-eksterjer-promo/aeroport',]);
        ChildCategory::create(['id' => 104, 'category_id' => $category->id, 'name' => 'Иное промо', 'key' => 'inoje', 'num_left' => 223 , 'num_right' => 224, 'keyword' => 'doma-i-zdanija-eksterjer-promo/inoje',]);
        ChildCategory::create(['id' => 103, 'category_id' => $category->id, 'name' => 'Кафе, рестораны промо', 'key' => 'kafe-restorany', 'num_left' => 217 , 'num_right' => 218, 'keyword' => 'doma-i-zdanija-eksterjer-promo/kafe-restorany',]);
        ChildCategory::create(['id' => 102, 'category_id' => $category->id, 'name' => 'Нежилые здания промо', 'key' => 'nezhiloje-zdanije', 'num_left' => 215 , 'num_right' => 216, 'keyword' => 'doma-i-zdanija-eksterjer-promo/nezhiloje-zdanije',]);
        ChildCategory::create(['id' => 143, 'category_id' => $category->id, 'name' => 'Вокзал промо', 'key' => 'vokzal', 'num_left' => 219 , 'num_right' => 220, 'keyword' => 'doma-i-zdanija-eksterjer-promo/vokzal',]);
        ChildCategory::create(['id' => 101, 'category_id' => $category->id, 'name' => 'Жилые дома промо', 'key' => 'zhiloj-dom', 'num_left' => 213 , 'num_right' => 214, 'keyword' => 'doma-i-zdanija-eksterjer-promo/zhiloj-dom',]);

        $category = Category::create(['id' => 105, 'name' => 'Помещения, интерьер промо', 'key' => 'pomeshhenija-interjer', 'type' => 'employer', 'num_left' => 226 , 'num_right' => 239, 'title_ograph' => 'Маркетплейс в вашем учреждении!', 'keyword' => 'pomeshhenija-interjer-promo']);
        ChildCategory::create(['id' => 146, 'category_id' => $category->id, 'name' => 'Аэропорт промо', 'key' => 'aeroport', 'num_left' => 235 , 'num_right' => 236, 'keyword' => 'pomeshhenija-interjer-promo/aeroport',]);
        ChildCategory::create(['id' => 109, 'category_id' => $category->id, 'name' => 'Иное  промо', 'key' => 'inoje', 'num_left' => 237 , 'num_right' => 238, 'keyword' => 'pomeshhenija-interjer-promo/inoje',]);
        ChildCategory::create(['id' => 108, 'category_id' => $category->id, 'name' => 'Кафе, рестораны промо', 'key' => 'kafe-restorany', 'num_left' => 231 , 'num_right' => 232, 'keyword' => 'pomeshhenija-interjer-promo/kafe-restorany',]);
        ChildCategory::create(['id' => 107, 'category_id' => $category->id, 'name' => 'Нежилые здания  промо', 'key' => 'nezhilyje-zdanija', 'num_left' => 229 , 'num_right' => 230, 'keyword' => 'pomeshhenija-interjer-promo/nezhilyje-zdanija',]);
        ChildCategory::create(['id' => 145, 'category_id' => $category->id, 'name' => 'Вокзал промо', 'key' => 'vokzal', 'num_left' => 233 , 'num_right' => 234, 'keyword' => 'pomeshhenija-interjer-promo/vokzal',]);
        ChildCategory::create(['id' => 106, 'category_id' => $category->id, 'name' => 'Жилые дома промо', 'key' => 'zhilyje-doma', 'num_left' => 227 , 'num_right' => 228, 'keyword' => 'pomeshhenija-interjer-promo/zhilyje-doma',]);

        Category::create(['id' => 110, 'name' => 'На своей продукции промо', 'key' => 'sobstvennaja-produkcija', 'type' => 'employer', 'num_left' => 268 , 'num_right' => 269, 'title_ograph' => 'Заработай на рекламе на своей продукции!', 'keyword' => 'sobstvennaja-produkcija-promo']);

        $category = Category::create(['id' => 111, 'name' => 'Ландшафт промо', 'key' => 'landshaft', 'type' => 'employer', 'num_left' => 258 , 'num_right' => 263, 'title_ograph' => 'Реклама в дизайне ландшафта!', 'keyword' => 'landshaft-promo']);
        ChildCategory::create(['id' => 112, 'category_id' => $category->id, 'name' => 'Личное пространство промо', 'key' => 'lichnoje-prostranstvo', 'num_left' => 259 , 'num_right' => 260, 'keyword' => 'landshaft-promo/lichnoje-prostranstvo',]);
        ChildCategory::create(['id' => 113, 'category_id' => $category->id, 'name' => 'Общественное пространство  промо', 'key' => 'obshhestvennoje-prostranstvo', 'num_left' => 261 , 'num_right' => 262, 'keyword' => 'landshaft-promo/obshhestvennoje-prostranstvo',]);

        Category::create(['id' => 114, 'name' => 'Билборды / рекламные конструкции промо', 'key' => 'bilbordy-reklamnyje-konstrukcii', 'type' => 'employer', 'num_left' => 240 , 'num_right' => 241, 'title_ograph' => 'Размести рекламу на своём билборде!', 'keyword' => 'bilbordy-reklamnyje-konstrukcii-promo']);

        $category = Category::create(['id' => 115, 'name' => 'Медиапроекты промо', 'key' => 'mediaprojekty', 'type' => 'employer', 'num_left' => 242 , 'num_right' => 257, 'title_ograph' => 'Найди спонсора для своего медиапроекта!', 'keyword' => 'mediaprojekty-promo']);
        ChildCategory::create(['id' => 121, 'category_id' => $category->id, 'name' => 'Другие проекты промо', 'key' => 'drugije-projekty', 'num_left' => 253 , 'num_right' => 254, 'keyword' => 'mediaprojekty-promo/drugije-projekty',]);
        ChildCategory::create(['id' => 118, 'category_id' => $category->id, 'name' => 'Кино промо', 'key' => 'kino', 'num_left' => 247 , 'num_right' => 248, 'keyword' => 'mediaprojekty-promo/kino',]);
        ChildCategory::create(['id' => 120, 'category_id' => $category->id, 'name' => 'Музыка промо', 'key' => 'muzyka', 'num_left' => 251 , 'num_right' => 252, 'keyword' => 'mediaprojekty-promo/muzyka',]);
        ChildCategory::create(['id' => 122, 'category_id' => $category->id, 'name' => 'Промокампании промо', 'key' => 'promokampanii', 'num_left' => 255 , 'num_right' => 256, 'keyword' => 'mediaprojekty-promo/promokampanii',]);
        ChildCategory::create(['id' => 117, 'category_id' => $category->id, 'name' => 'Радио промо', 'key' => 'radio', 'num_left' => 245 , 'num_right' => 246, 'keyword' => 'mediaprojekty-promo/radio',]);
        ChildCategory::create(['id' => 116, 'category_id' => $category->id, 'name' => 'Телепередачи промо', 'key' => 'teleperedacha', 'num_left' => 243 , 'num_right' => 244, 'keyword' => 'mediaprojekty-promo/teleperedacha',]);
        ChildCategory::create(['id' => 119, 'category_id' => $category->id, 'name' => 'Видеоклипы  промо', 'key' => 'videoklip', 'num_left' => 249 , 'num_right' => 250, 'keyword' => 'mediaprojekty-promo/videoklip',]);

        Category::create(['id' => 148, 'name' => 'Печатная продукция промо', 'key' => 'pechatnaja-produkcija', 'type' => 'employer', 'num_left' => 282 , 'num_right' => 283, 'title_ograph' => '', 'keyword' => 'pechatnaja-produkcija-promo']);

        Category::create(['id' => 123, 'name' => 'Социальные сети и сайты промо', 'key' => 'socialnyje-seti-i-sajty', 'type' => 'employer', 'num_left' => 274 , 'num_right' => 275, 'title_ograph' => 'Ищи рекламодателя для своих сайтов!', 'keyword' => 'socialnyje-seti-i-sajty-promo']);

        $category = Category::create(['id' => 124, 'name' => 'Мероприятия промо', 'key' => 'meroprijatija', 'type' => 'employer', 'num_left' => 264 , 'num_right' => 265, 'title_ograph' => 'Найди спонсора твоего мероприятия!', 'keyword' => 'meroprijatija-promo']);

        Category::create(['id' => 125, 'name' => 'Путешествия промо', 'key' => 'puteshestvija', 'type' => 'employer', 'num_left' => 266 , 'num_right' => 267, 'title_ograph' => 'В дорогу? Заработай на рекламе!', 'keyword' => 'puteshestvija-promo']);

        Category::create(['id' => 126, 'name' => 'Спортивная экипировка промо', 'key' => 'odezhda-ekipirovka', 'type' => 'employer', 'num_left' => 270 , 'num_right' => 271, 'title_ograph' => 'Найди спонсора для своей команды!', 'keyword' => 'odezhda-ekipirovka-promo']);

        Category::create(['id' => 127, 'name' => 'Нестандартное место промо', 'key' => 'nestandartnoje-mesto', 'type' => 'employer', 'num_left' => 280 , 'num_right' => 281, 'title_ograph' => 'Рекламируй и зарабатывай!', 'keyword' => 'nestandartnoje-mesto-promo']);

        Category::create(['id' => 128, 'name' => 'Строительство промо', 'key' => 'stroitelstvo', 'type' => 'employer', 'num_left' => 272 , 'num_right' => 273, 'title_ograph' => 'Найди инвестора под свой проект!', 'keyword' => 'stroitelstvo-promo']);

        $category = Category::create(['id' => 130, 'name' => 'Общественный транспорт промо', 'key' => 'obshhestvennyj-transport', 'type' => 'employer', 'num_left' => 172 , 'num_right' => 189, 'title_ograph' => '', 'keyword' => 'obshhestvennyj-transport-promo']);
        ChildCategory::create(['id' => 131, 'category_id' => $category->id, 'name' => 'Автобусы промо', 'key' => 'avtobusy', 'num_left' => 173 , 'num_right' => 174, 'keyword' => 'obshhestvennyj-transport-promo/avtobusy',]);
        ChildCategory::create(['id' => 136, 'category_id' => $category->id, 'name' => 'Другое промо', 'key' => 'drugoje', 'num_left' => 187 , 'num_right' => 188, 'keyword' => 'obshhestvennyj-transport-promo/drugoje',]);
        ChildCategory::create(['id' => 132, 'category_id' => $category->id, 'name' => 'Электрички промо', 'key' => 'elektrichki-pojezda', 'num_left' => 181 , 'num_right' => 182, 'keyword' => 'obshhestvennyj-transport-promo/elektrichki-pojezda',]);
        ChildCategory::create(['id' => 135, 'category_id' => $category->id, 'name' => 'Маршрутные такси промо', 'key' => 'marshrutnyje-taksi', 'num_left' => 185 , 'num_right' => 186, 'keyword' => 'obshhestvennyj-transport-promo/marshrutnyje-taksi',]);
        ChildCategory::create(['id' => 138, 'category_id' => $category->id, 'name' => 'Метро промо', 'key' => 'metro', 'num_left' => 179 , 'num_right' => 180, 'keyword' => 'obshhestvennyj-transport-promo/metro',]);
        ChildCategory::create(['id' => 137, 'category_id' => $category->id, 'name' => 'Поезда промо', 'key' => 'pojezda', 'num_left' => 183 , 'num_right' => 184, 'keyword' => 'obshhestvennyj-transport-promo/pojezda',]);
        ChildCategory::create(['id' => 133, 'category_id' => $category->id, 'name' => 'Трамваи промо', 'key' => 'tramvai', 'num_left' => 177 , 'num_right' => 178, 'keyword' => 'obshhestvennyj-transport-promo/tramvai',]);
        ChildCategory::create(['id' => 134, 'category_id' => $category->id, 'name' => 'Троллейбусы промо', 'key' => 'trollejbusy', 'num_left' => 175 , 'num_right' => 176, 'keyword' => 'obshhestvennyj-transport-promo/trollejbusy',]);

        Category::create(['id' => 150, 'name' => 'Стрит-арт промо', 'key' => 'stritart', 'type' => 'employer', 'num_left' => 284 , 'num_right' => 285, 'title_ograph' => '', 'keyword' => 'stritart-promo']);

    }
}
