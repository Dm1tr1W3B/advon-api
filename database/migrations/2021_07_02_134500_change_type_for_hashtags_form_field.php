<?php

use App\Models\FormField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeForHashtagsFormField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FormField::where('key', 'hashtags')->update(['type' => 'hashtags']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        FormField::where('key', 'hashtags')->update(['type' => 'text']);
    }
}
