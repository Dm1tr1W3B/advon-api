<?php
namespace Database\Seeders\OldData;


use App\Http\Helpers\ImageHelper;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateOldCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $companies = DB::connection('old_advon')
            ->table("bff_shops")
            ->distinct()
            ->get();
        $date = Carbon::now();
        $output = new ConsoleOutput();
        $output->write("Seed old Companies...");

        $progressBar = new ProgressBar($output, $companies->count());
        $progressBar->start();
        foreach ($companies as $company) {

            $company_lang = DB::connection('old_advon')
                ->table("bff_shops_lang")
                ->where(['id' => $company->id, 'lang' => 'ru'])->first();
            try {
                $c = Company::firstOrNew(["id" => $company->id]);

                if (!$c->exists) {

                    /*
                    $path = Str::after($company->img_m, '//');
                    $img = ImageHelper::createPhotoFromURL('companies/old', "https://" . $path);
                    $img_id = $img->id;

                    if (!isset($img_id))
                        $img_id = null;

                    */

                    $phone = null;
                    if (!empty($company->phone)) {

                        preg_match_all ( '/[0-9]/', $company->phone,  $matches );
                        if (!empty($matches[0])) {
                            foreach ($matches[0] as $v) {
                                $phone .= $v;
                            }
                        }
                    }

                    $logo_id = null;
                    if (!empty($company->logo)) {
                        $oldLogoPath = 'https://advon.me/files/images/shop/logo/0/'. $company->id.'l' .$company->logo;
                        $logo = ImageHelper::createPhotoFromURL('companies/old', $oldLogoPath);

                        if (!empty($logo->id))
                            $logo_id = $logo->id;
                    }


                    if ($company->reg1_country)
                        $country = DB::connection('old_advon')->table('bff_regions')->find($company->reg1_country);
                    if ($company->reg2_region)
                        $region = DB::connection('old_advon')->table('bff_regions')->find($company->reg2_region);
                    if ($company->reg3_city)
                        $city = DB::connection('old_advon')->table('bff_regions')->find($company->reg3_city);

                    $c->fill([

                        "owner_id" => $company->user_id,
                        "name" => $company_lang->title_edit,
                        "description" => $company_lang->descr,
                        //"photo_id" => $img_id,
                        "email" => $company->site_mail == '' ? null : $company->site_mail,
                        "phone" => $phone,
                        "country" => isset($country) ? $country->title_ru : "",
                        "region" => isset($region) ? $region->title_ru : "",
                        "city" => isset($city) ? $city->title_ru : "",
                        "created_at" => $company->created,
                        "hashtags" => $company->tags,
                        "latitude" => $company->addr_lat,
                        "longitude" => $company->addr_lon,
                        'site_url'  => $company->site,
                        'logo_id' => $logo_id,
                        'is_top_at' => '2021-08-27 23:59:59',
                        'is_allocate_at' => '2021-08-27 23:59:59',
                        'is_verification' => false,
                        'top_residue_days' => 0,
                        'allocate_residue_days' => 0,

                    ])->save();

                    $photo_ids = [];
                    DB::connection('old_advon')
                        ->table("bff_shops_images")
                        ->where('user_id', $company->user_id)
                        ->get()
                        ->each(function ($shopsImages) use (&$photo_ids) {

                            $oldPath = 'https://advon.me/files/images/shops/'. $shopsImages->dir.'/'.$shopsImages->item_id.'o'.$shopsImages->filename;

                            $image = ImageHelper::createPhotoFromURL('companies/old', $oldPath);

                            if (!isset($image->id))
                                return true;

                            $photo_ids[] = $image->id;
                        });

                    if (!empty($photo_ids)) {
                        $c->images()->attach($photo_ids);
                        $c->photo_id =  $photo_ids[0];
                    }

                    $c->save();




                    $progressBar->advance();
                }
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
        $progressBar->finish();
    }
}
