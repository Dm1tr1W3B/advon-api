<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        $moderator = Role::where('name', 'moderator')->firstOrFail();

        $permissions = Permission::whereIn('key', [
            'browse_admin',
            'browse_advertisement_author_complaints',
            'read_advertisement_author_complaints',
            'delete_advertisement_author_complaints',
            'read_advertisement_complaints',
            'browse_advertisement_complaints',
            'delete_advertisement_complaints'
        ])->orWhere((function ($query) {
            $query->whereIn('table_name', [
                'languages',
                'users',
                'front_variables_lang',
                'companies',
                'advertisements',
                'categories',
                'child_categories',
                'complaint_types',

            ]);
        }))->get();
        $moderator->permissions()->sync(
            $permissions->pluck('id')->all()
        );
    }
}
