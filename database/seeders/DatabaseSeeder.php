<?php

namespace Database\Seeders;

use Database\Seeders\OldData\AdvertisementSeeder;
use Database\Seeders\OldData\CategoryFormFieldSeeder;
use Database\Seeders\OldData\CategorySeeder;
use Database\Seeders\OldData\CreateOldCompaniesSeeder;
use Database\Seeders\OldData\CreateOldUsersSeeder;
use Database\Seeders\OldData\FormFieldSeeder;
use Database\Seeders\OldData\MainPhotoForAdvertSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use TCG\Voyager\Traits\Seedable;

class DatabaseSeeder extends Seeder
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

        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public/users/old');
        $file->cleanDirectory('storage/app/public/companies/old');
        $file->cleanDirectory('storage/app/public/advertisements/old');


        $this->seed(MenusTableSeeder::class);
//
        $this->seed(RolesTableSeeder::class);
        $this->seed(VoyagerSeeder::class);
        $this->seed(CreateLangs::class);
        $this->seed(CreateOldUsersSeeder::class);
        $this->seed(UsersSeeder::class);
        $this->seed(CreateContactTypes::class);
        $this->seed(FormFieldSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(CreateOldCompaniesSeeder::class);
        $this->seed(CategoryFormFieldSeeder::class);
        $this->seed(AdvertisementSeeder::class);
        $this->seed(MainPhotoForAdvertSeeder::class);

    }
}
