<?php
namespace Database\Seeders;

use Database\Seeders\Bread\BreadForAdvertisementComplaintSeeder;
use Database\Seeders\Bread\BreadForAdvertisementsSeeder;
use Database\Seeders\Bread\BreadForAuthorComplaintSeeder;
use Database\Seeders\Bread\BreadForBannerSeeder;
use Database\Seeders\Bread\BreadForBonusSeeder;
use Database\Seeders\Bread\BreadForCategoriesSeeder;
use Database\Seeders\Bread\BreadForChildCategoriesSeeder;
use Database\Seeders\Bread\BreadForCompaniesSeeder;
use Database\Seeders\Bread\BreadForComplaintTypesSeeder;
use Database\Seeders\Bread\BreadForFeedbacksSeeder;
use Database\Seeders\Bread\BreadForFeedbackTypesSeeder;
use Database\Seeders\Bread\BreadForFrontVariablesLangSeeder;
use Database\Seeders\Bread\BreadForLangSeeder;
use Database\Seeders\Bread\BreadForSEOSeeder;
use Database\Seeders\Bread\BreadForTransactionBalancesSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Setting;
use TCG\Voyager\Traits\Seedable;

class VoyagerSeeder extends Seeder
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
        //Clear Data Before Seeding
        //descending so that there are no errors due to relationship
        DataRow::truncate();
        DataType::truncate();
        Permission::truncate();
        MenuItem::truncate();
        Setting::truncate();
        DB::table('permission_role')->truncate();
        //<-----End Clear------>

        $this->seed(DataTypesTableSeeder::class);
        $this->seed(DataRowsTableSeeder::class);
        $this->seed(PermissionsTableSeeder::class);
        $this->seed(PermissionRoleTableSeeder::class);
        $this->seed(BreadForLangSeeder::class);
        $this->seed(BreadForFrontVariablesLangSeeder::class);
        $this->seed(BreadForCompaniesSeeder::class);
        $this->seed(BreadForCompaniesSeeder::class);
        $this->seed(MenuItemsTableSeeder::class);
        $this->seed(SettingsTableSeeder::class);
        $this->seed(BreadForAdvertisementsSeeder::class);
        $this->seed(BreadForCategoriesSeeder::class);
        $this->seed(BreadForChildCategoriesSeeder::class);
        $this->seed(BreadForComplaintTypesSeeder::class);
        $this->seed(BreadForAuthorComplaintSeeder::class);
        $this->seed(BreadForAdvertisementComplaintSeeder::class);
        $this->seed(BreadForBannerSeeder::class);
        $this->seed(BreadForBonusSeeder::class);
        $this->seed(BreadForTransactionBalancesSeeder::class);
        $this->seed(BreadForSEOSeeder::class);
        $this->seed(BreadForFeedbackTypesSeeder::class);
        $this->seed(BreadForFeedbacksSeeder::class);


    }
}
