<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Traits\Seedable;
use Database\Seeders\VoyagerSeeder;

class MyDatabaseSeeder extends Seeder
{
    use Seedable;


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(VoyagerSeeder::class);

    }
}
