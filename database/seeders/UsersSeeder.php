<?php
namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use TCG\Voyager\Traits\Seedable;

class UsersSeeder extends Seeder
{
    use Seedable;
    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew(['email' => 'admin@admin.com']);

            $user->fill([
                'name' => 'admin',
                'role_id' => 1,
                'password' => Hash::make('admin')
            ])->save();

        $user = User::firstOrNew(['email' => 'moderator@moderator.com','id'=>3]);

            $user->fill([
                'name' => 'moderator',
                'role_id' => 3,
                'password' => Hash::make('moderator')
            ])->save();

    }
}
