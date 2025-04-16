<?php


use App\Models\Category;
use App\Models\FormField;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsShowSmallCardForCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $price = FormField::where('key','price')->first();
        $negotiable = FormField::where('key','negotiable')->first();
        $bargaining = FormField::where('key','bargaining')->first();

        DB::update('update category_form_field set is_show_small_card = true where form_field_id = ?', [$price->id]);
        DB::update('update category_form_field set is_show_small_card = true where form_field_id = ?', [$negotiable->id]);
        DB::update('update category_form_field set is_show_small_card = true where form_field_id = ?', [$bargaining->id]);


        $reachAudience = FormField::where('key','reach_audience')->first();
        Category::where('type', 'performer')
            ->get()
            ->each(function ($category) use ($reachAudience) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $reachAudience->id]);
        });



        $amount = FormField::where('key','amount')->first();
        Category::whereNotIn('id', [59, 6, 31, 36, 42, 46, 53, 54, 55, 147, 57, 58, 149])
            ->get()
            ->each(function ($category) use ($amount) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $amount->id]);
            });

        $length = FormField::where('key','length')->first();
        $width = FormField::where('key','width')->first();
        Category::where('type', 'performer')
            ->whereIn('key', [
                'lichnyje-veshhi',
                'avtotransport',
                'mototransport',
                'gruzoviki',
                'vodnaja-tehnika',
                'letatelnyje-apparaty',
                'doma-i-zdanija-eksterjer',
                'pomeshhenija-interjer',
                'landshaft',
                'bilbordy-reklamnyje-konstrukcii',
                'sobstvennaja-produkcija',
                'stritart'
                ])
            ->get()
            ->each(function ($category) use ($length, $width) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $length->id]);
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $width->id]);
            });

        $travel_abroad = FormField::where('key','travel_abroad')->first();
        Category::where('type', 'performer')
            ->whereIn('key', [
                'gruzoviki',
                'vodnaja-tehnika',
                'letatelnyje-apparaty',
                'puteshestvija',
            ])
            ->get()
            ->each(function ($category) use ($travel_abroad) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $travel_abroad->id]);
            });


        $ready_for_political_advertising = FormField::where('key','ready_for_political_advertising')->first();
        Category::where('type', 'employer')
            ->get()
            ->each(function ($category) use ($ready_for_political_advertising) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $ready_for_political_advertising->id]);
            });

        Category::where('type', 'performer')
            ->whereIn('key', [
                'doma-i-zdanija-eksterjer',
                'bilbordy-reklamnyje-konstrukcii',
                'meroprijatija',
                'pechatnaja-produkcija',
                'nestandartnoje-mesto',
                'obshhestvennyj-transport'
            ])
            ->get()
            ->each(function ($category) use ($travel_abroad) {
                DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $travel_abroad->id]);
            });

        $date_start = FormField::where('key','date_start')->first();
        $date_finish = FormField::where('key','date_finish')->first();
        $category = Category::where('type', 'performer')->where('key', 'odezhda-ekipirovka')->first();
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $date_start->id]);
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $date_finish->id]);

        $date_of_the = FormField::where('key','date_of_the')->first();
        $category = Category::where('type', 'performer')->where('key', 'puteshestvija')->first();
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $date_of_the->id]);

        $category = Category::where('type', 'performer')->where('key', 'meroprijatija')->first();
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $date_of_the->id]);


        $deadline_date = FormField::where('key','deadline_date')->first();
        $sample = FormField::where('key','sample')->first();
        $link_page = FormField::where('key','link_page')->first();
        $attendance = FormField::where('key','attendance')->first();

        $category = Category::where('type', 'performer')->where('key', 'mediaprojekty')->first();
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $deadline_date->id]);
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $sample->id]);
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $link_page->id]);
        DB::update('update category_form_field set is_show_small_card = true where category_id = ? and form_field_id = ?', [$category->id, $attendance->id]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('update category_form_field set is_show_small_card = false');
    }
}
