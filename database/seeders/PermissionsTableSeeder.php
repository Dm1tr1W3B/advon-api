<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $keys = [
            'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'read_advertisement_author_complaints',
            'browse_advertisement_author_complaints',
            'delete_advertisement_author_complaints',
            'read_advertisement_complaints',
            'browse_advertisement_complaints',
            'delete_advertisement_complaints',
            'read_categories',
            'edit_categories',
            'read_child_categories',
            'browse_transaction_balances',
            'browse_categories',
            'browse_child_categories',
            'browse_advertisements',
            'read_advertisements',
            'edit_advertisements',
            'delete_advertisements',
            'moderate_advertisements',
            'browse_feedback',
            'read_feedback',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key' => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');

        Permission::generateFor('languages');

        Permission::generateFor('front_variables_lang');
        Permission::generateFor('companies');
        //Permission::generateFor('advertisements');
        //Permission::generateFor('categories');
        //Permission::generateFor('child_categories');
        Permission::generateFor('complaint_types');
        Permission::generateFor('banners');
        Permission::generateFor('bonuses');
        Permission::generateFor('s_e_o_s');
        Permission::generateFor('feedback_types');




    }
}
