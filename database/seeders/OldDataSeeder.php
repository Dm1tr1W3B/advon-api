<?php
namespace Database\Seeders;

use Database\Seeders\OldData\CreateOldAdvertisementsSeeder;
use Database\Seeders\OldData\CreateOldCompaniesSeeder;
use Database\Seeders\OldData\CreateOldUsersSeeder;
use Illuminate\Database\Seeder;

use TCG\Voyager\Traits\Seedable;

class OldDataSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__ . '/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->seed(CreateOldUsersSeeder::class);
        $this->seed(CreateOldCompaniesSeeder::class);
        $this->seed(CreateOldAdvertisementsSeeder::class);

    }
}
