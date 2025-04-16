<?php
namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class CreateLangs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lang = Language::firstOrCreate([
            'key' => 'ru',
            'name'=>"Русский",
        ]);
        if (!$lang->exist) {
            $lang->fill([
                'enabled'=>true,
                'rtl'=>false,

            ])->save();
        }
    }
}
