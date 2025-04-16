<?php
namespace Database\Seeders\OldData;


use App\Http\Helpers\ImageHelper;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateOldUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $users = DB::connection('old_advon')
            ->table("bff_users")
            ->whereNotNull('email')
            ->distinct()
            ->get();
        $output = new ConsoleOutput();

        $progressBar = new ProgressBar($output, $users->count());
        $output->write("Seed old Users...");

        $progressBar->start();
        $date = Carbon::now();

        foreach ($users as $user) {
            try {
                $avatar=[];
                $u = User::firstOrNew(["id" => $user->user_id, 'email' => $user->email]);
                $folder = 'users/old/' . $date->monthName . $date->year;
                if (!$u->exists) {
                    $path = Str::after($user->img_m, '//');
                    if ($path) {
                        $img = Image::make("https://" . $path);
                        $filename = Str::afterLast($path, '/');

                        $imgPath = $folder . '/' . $filename;
                        $imgPath = isset($imgPath) ? $imgPath : "";
                        $avatar = ['avatar' => $imgPath];

                        if (!File::exists(public_path('storage/' . $folder)))
                            File::makeDirectory(public_path('storage/' . $folder), 0755, true);
                        $img->save(public_path('storage/' . $imgPath));
                    }


                    $name = $user->name ? $user->name : 'Нет Имени';

                    $phone = null;
                    if (!empty($user->phone_number)) {

                        preg_match_all ( '/[0-9]/', $user->phone_number,  $matches );
                        if (!empty($matches[0])) {
                            foreach ($matches[0] as $v) {
                                $phone .= $v;
                            }
                        }
                    }

                    if ($user->reg1_country)
                        $country = DB::connection('old_advon')->table('bff_regions')->find($user->reg1_country);
                    if ($user->reg2_region)
                        $region = DB::connection('old_advon')->table('bff_regions')->find($user->reg2_region);
                    if ($user->reg3_city)
                        $city = DB::connection('old_advon')->table('bff_regions')->find($user->reg3_city);
                    if ($u->existed)
                        $avatar = [];
                    $u->fill(array_merge([

                        "name" => $name,
                        "password" => $user->password,
                        "role_id" => 2,
                        "created_at" => $user->created,
                        "description" => $user->descr,
                        'currency_id' => 1,
                        "balance" => $user->balance,
                        "latitude" => $user->addr_lat,
                        "longitude" => $user->addr_lon,
                        "phone" => $phone,
                        "country" => isset($country) ? $country->title_ru : "",
                        "region" => isset($region) ? $region->title_ru : "",
                        "city" => isset($city) ? $city->title_ru : "",
                        'bonus_balance' => 0.00,
                        'ref_code' => hash('crc32', $u->id),
                    ], $avatar))->save();
                    $progressBar->advance(1);

                    $photo_ids = [];
                    $oldUserImages = DB::connection('old_advon')
                        ->table("bff_users_images")
                        ->distinct()
                        ->where('user_id', $u->id)
                        ->get()
                        ->each(function ($userImages) use (&$photo_ids, $folder) {

                            $oldPath = 'https://advon.me/files/images/users/'. $userImages->dir.'/'.$userImages->user_id.'b'.$userImages->filename;

                            $image = ImageHelper::createPhotoFromURL($folder, $oldPath);

                            if (!isset($image->id))
                                return true;

                            $photo_ids[] = $image->id;
                        });

                    $u->images()->attach($photo_ids);

                    UserSetting::create([
                        'user_id' => $u->id,
                        'is_hide_user' => false,
                        'is_hide_company' => false,
                        'is_receive_news' => false,
                        'is_receive_messages_by_email' => true,
                        'is_receive_comments_by_email' => false,
                        'is_receive_price_favorite_by_email' => false,
                        'is_receive_messages_by_phone' => false,
                        'is_receive_comments_by_phone' => false,
                        'is_receive_price_favorite_by_phone' => false,
                    ]);


                }
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }

        }
        $progressBar->finish();

    }
}
