<?php

use App\Models\FormField;
use App\Models\FrontVariablesLang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHint3ForCategoryFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FormField::get()
            ->each(function ($formField) {

                if ($formField->key == 'length' || $formField->key == 'width') {
                    FrontVariablesLang::firstOrCreate(['key' => $formField->key . '_hint', 'value' => 'см']);
                    return true;
                }

                if ($formField->key == 'reach_audience') {
                    FrontVariablesLang::firstOrCreate(['key' => $formField->key . '_hint', 'value' => 'чел']);
                    return true;
                }

                if ($formField->key == 'amount') {
                    FrontVariablesLang::firstOrCreate(['key' => $formField->key . '_hint', 'value' => 'шт']);
                    return true;
                }

                if ($formField->key == 'photo_report')
                    FrontVariablesLang::where('key', $formField->key)->update(['value' => 'Предоставления фотоотчета']);

                FrontVariablesLang::firstOrCreate(['key' => $formField->key . '_hint', 'value' => '']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
