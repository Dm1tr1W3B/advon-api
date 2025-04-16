<?php


namespace Database\Seeders\OldData;


use App\Http\Helpers\ImageHelper;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class MainPhotoForAdvertSeeder
{
    /**
     *
     */
    public function run()
    {
        $output = new ConsoleOutput();

        $ads=Advertisement::all();
        $output->write("Seed Main Photos for Adverts...");
        $progressBar = new ProgressBar($output, $ads->count());
        $progressBar->start();
        $ads->each(function ($item) use ($progressBar) {
            if (!$item->photo_id)
                try {

                    $advert = DB::connection('old_advon')
                        ->table("bff_bbs_items")
                        ->find($item->id);
                    $path = Str::after($advert->img_m, '//');
                    $img = ImageHelper::createPhotoFromURL('advertisements/old', "https://" . $path);
                    $img_id = $img->id;
                    $item->photo_id = $img_id;
                    $item->save();
                    $progressBar->advance();
                } catch (\Exception $exception) {

                }
        });
        $progressBar->finish();
    }
}
