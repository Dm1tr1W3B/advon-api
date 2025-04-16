<?php

namespace Database\Seeders\OldData;


use App\Http\Helpers\ImageHelper;
use App\Models\Advertisement;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateOldAdvertisementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $advertisements = DB::connection('old_advon')
            ->table("bff_bbs_items")
            ->distinct()
            ->get();
        $date = Carbon::now();
        $output = new ConsoleOutput();
        $output->write("Seed old Advertisements...");

        $progressBar = new ProgressBar($output, $advertisements->count());
        $progressBar->start();
        foreach ($advertisements as $advertisement) {


            try {

                $a = Advertisement::firstOrNew(["id" => $advertisement->id]);

                if (!$a->exists) {


                    if ($advertisement->reg1_country)
                        $country = DB::connection('old_advon')->table('bff_regions')->find($advertisement->reg1_country);
                    if ($advertisement->reg2_region)
                        $region = DB::connection('old_advon')->table('bff_regions')->find($advertisement->reg2_region);
                    if ($advertisement->reg3_city)
                        $city = DB::connection('old_advon')->table('bff_regions')->find($advertisement->reg3_city);

                    $hashtags = [];
                    if (!empty($advertisement->f21)) {
                        $hashtags = explode(',', $advertisement->f21);
                        $hashtags = array_map('trim', $hashtags);
                    }

                    $payment = 0;
                    $reach_audience = 0;
                    $travel_abroad = 0;
                    $ready_for_political_advertising = 0;
                    $photo_report = 0;
                    $make_and_place_advertising = 0;
                    $amount = 0;
                    $length = 0;
                    $width = 0;
                    $deadline_date = 0;
                    $date_start = 0;
                    $date_finish = 0;

                    // Личные вещи
                    if (in_array($advertisement->cat_id1, [7])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f6;
                        $travel_abroad = $advertisement->f7;
                        $ready_for_political_advertising = $advertisement->f8;
                        $photo_report = $advertisement->f9;
                        $make_and_place_advertising = $advertisement->f10;
                        $length = $advertisement->f14;
                        $width = $advertisement->f15;
                    }

                    // Автотранспорт
                    // Мототранспорт
                    // Грузовики
                    // Водная техника
                    // Летательные аппараты
                    if (in_array($advertisement->cat_id1, [11, 15, 16, 20, 26])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f6;
                        $length = $advertisement->f7;
                        $width = $advertisement->f8;
                        $travel_abroad = $advertisement->f9;
                        $ready_for_political_advertising = $advertisement->f10;
                        $photo_report = $advertisement->f11;
                        $make_and_place_advertising = $advertisement->f12;
                    }

                    // Помещения, интерьер
                    // Ландшафт
                    if (in_array($advertisement->cat_id1, [36, 42])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $length = $advertisement->f7;
                        $width = $advertisement->f8;

                        $ready_for_political_advertising = $advertisement->f9;
                        $photo_report = $advertisement->f10;
                        $make_and_place_advertising = $advertisement->f11;
                    }

                    // Дома и здания, экстерьер
                    // Билборды / рекламные конструкции
                    // Нестандартное место
                    if (in_array($advertisement->cat_id1, [31, 45, 57])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f6;
                        $length = $advertisement->f7;
                        $width = $advertisement->f8;

                        $ready_for_political_advertising = $advertisement->f10;
                        $photo_report = $advertisement->f11;
                        $make_and_place_advertising = $advertisement->f12;
                    }

                    // Медиапроекты
                    if (in_array($advertisement->cat_id1, [46])) {
                        $ready_for_political_advertising = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $make_and_place_advertising = $advertisement->f4;
                        $deadline_date = $advertisement->f6;
                    }

                    // Мероприятия
                    if (in_array($advertisement->cat_id1, [54])) {
                        $date_start = $advertisement->f1;
                        $date_finish = $advertisement->f2;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f4;
                        $length = $advertisement->f5;
                        $width = $advertisement->f6;
                        $ready_for_political_advertising = $advertisement->f7;
                        $photo_report = $advertisement->f8;
                        $make_and_place_advertising = $advertisement->f9;
                    }

                    // Путешествия
                    if (in_array($advertisement->cat_id1, [55])) {
                        $date_start = $advertisement->f1;
                        $date_finish = $advertisement->f2;
                        $reach_audience = $advertisement->f3;
                        $ready_for_political_advertising = $advertisement->f9;
                        $photo_report = $advertisement->f10;
                        $make_and_place_advertising = $advertisement->f11;
                        $travel_abroad = $advertisement->f15;
                    }

                    // На своей продукции
                    if (in_array($advertisement->cat_id1, [41])) {
                        $amount = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $length = $advertisement->f6;
                        $width = $advertisement->f7;
                        $ready_for_political_advertising = $advertisement->f8;
                        $photo_report = $advertisement->f9;

                    }

                    // Спортивная экипировка
                    if (in_array($advertisement->cat_id1, [56])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f4;
                        $travel_abroad = $advertisement->f5;
                        $ready_for_political_advertising = $advertisement->f6;
                        $photo_report = $advertisement->f7;
                        $make_and_place_advertising = $advertisement->f8;
                    }

                    // Строительство
                    if (in_array($advertisement->cat_id1, [58])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $make_and_place_advertising = $advertisement->f4;
                        $length = $advertisement->f5;
                        $amount = $advertisement->f6;
                        $width = $advertisement->f7;
                        $ready_for_political_advertising = $advertisement->f9;
                        $photo_report = $advertisement->f10;
                    }

                    // Социальные сети и сайты
                    if (in_array($advertisement->cat_id1, [53])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $ready_for_political_advertising = $advertisement->f6;
                    }

                    // Тело
                    if (in_array($advertisement->cat_id1, [59])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $travel_abroad = $advertisement->f6;
                        $ready_for_political_advertising = $advertisement->f7;
                        $photo_report = $advertisement->f8;
                        $make_and_place_advertising = $advertisement->f9;
                    }

                    // Животные
                    if (in_array($advertisement->cat_id1, [6])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f7;
                        $ready_for_political_advertising = $advertisement->f8;
                        $photo_report = $advertisement->f9;
                        $make_and_place_advertising = $advertisement->f10;
                        $travel_abroad = $advertisement->f12;
                    }

                    // Печатная продукция
                    if (in_array($advertisement->cat_id1, [147])) {
                        $reach_audience = $advertisement->f3;
                        $ready_for_political_advertising = $advertisement->f4;
                        $make_and_place_advertising = $advertisement->f5;
                        $length = $advertisement->f6;
                        $width = $advertisement->f7;
                    }

                    // Стрит-арт
                    if (in_array($advertisement->cat_id1, [149])) {
                        $width = $advertisement->f2;
                        $reach_audience = $advertisement->f3;
                        $length = $advertisement->f4;
                        $ready_for_political_advertising = $advertisement->f6;
                        $photo_report = $advertisement->f7;
                    }

                    // Общественный транспорт
                    if (in_array($advertisement->cat_id1, [62])) {
                        $payment = $advertisement->f1;
                        $reach_audience = $advertisement->f3;
                        $amount = $advertisement->f4;
                        $length = $advertisement->f5;
                        $width = $advertisement->f6;
                        $travel_abroad = $advertisement->f7;
                        $ready_for_political_advertising = $advertisement->f8;
                        $photo_report = $advertisement->f9;
                        $make_and_place_advertising = $advertisement->f10;
                    }

                    if ($advertisement->cat_type == 1) {
                        $amount = $advertisement->f1;
                        $ready_for_political_advertising = $advertisement->f2;
                        $make_and_place_advertising = $advertisement->f3;
                    }



                    $a->fill([
                        'type' => $advertisement->cat_type == 1 ? 'employer' : 'performer',
                        "user_id" => $advertisement->user_id,
                        "company_id" => $advertisement->shop_id == 0 ? null : $advertisement->shop_id,
                        'category_id' => $advertisement->cat_id1,
                        'child_category_id' => $advertisement->cat_id2 == 0 ? null : $advertisement->cat_id2,
                        'title' => $advertisement->title,
                        'description' => $advertisement->descr,
                        'price' => $advertisement->price,
                        'currency_id' => 1,
                        'price_type' => $advertisement->price_ex,

                        'is_published' => true,
                        'published_at' => '2021-09-27 14:04:42.000',
                        'video_url' => '',
                        'is_hide' => false,

                        'hashtags' => $hashtags,

                        'payment' => $payment,
                        'reach_audience' => $reach_audience,
                        'travel_abroad' => $travel_abroad,
                        'ready_for_political_advertising' => $ready_for_political_advertising,
                        'photo_report' => $photo_report,
                        'make_and_place_advertising' => $make_and_place_advertising,
                        'amount' => $amount,
                        'length' => $length,
                        'width' => $width,
                        'video' => '', // +

                        'sample' => '', // +
                        'deadline_date' => $deadline_date,
                        'link_page' => $advertisement->f21,
                        'attendance' => '', // +
                        'date_of_the' => 0, // +
                        'date_start' => $date_start,
                        'date_finish' => $date_finish,

                        "country" => isset($country) ? $country->title_ru : "",
                        "region" => isset($region) ? $region->title_ru : "",
                        "city" => isset($city) ? $city->title_ru : "",

                        "latitude" => $advertisement->addr_lat,
                        "longitude" => $advertisement->addr_lon,

                        'views_total' => 0,
                        'views_today' => 0,
                        'photo_id' => null,
                        'views_contact' => 0,
                        'is_allocate_at' => '2021-08-27 23:59:59',
                        'is_top_country_at' => '2021-08-27 23:59:59',
                        'is_urgent_at' => '2021-08-27 23:59:59',
                        'is_moderate' => false,
                        'top_country_residue_days' => 0,
                        'allocate_residue_days' => 0,
                        'urgent_residue_days' => 0,

                        "created_at" => $advertisement->created,

                    ])->save();

                    $photo_ids = [];
                    DB::connection('old_advon')
                        ->table("bff_bbs_items_images")
                        ->where('item_id', $advertisement->id)
                        ->get()
                        ->each(function ($itemImages) use (&$photo_ids) {

                            $oldPath = 'https://advon.me/files/images/items/' . $itemImages->dir . '/' . $itemImages->item_id . 'o' . $itemImages->filename;

                            $image = ImageHelper::createPhotoFromURL('product_additional_photos/old', $oldPath);

                            if (!isset($image->id))
                                return true;

                            $photo_ids[] = $image->id;
                        });

                    if (!empty($photo_ids)) {
                        $a->images()->attach($photo_ids);
                        $a->photo_id = $photo_ids[0];
                    }

                    $a->save();


                    $progressBar->advance();
                }
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
        $progressBar->finish();
    }
}
