<?php

namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:promotion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'daily update is_top_country_at  for advertisements and is_top_at for companies. Checking an overdue promotion';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start advertisements.');

        $dateNow = new DateTime('NOW');

        DB::table('advertisements')
            ->where('is_top_country_at', '<', $dateNow)
            ->whereRaw('is_top_country_at != created_at')
            ->get()
            ->each(function ($advertisement) {
                $this->info($advertisement->id);
                DB::update('update advertisements set is_top_country_at = ? where id = ?', [$advertisement->created_at, $advertisement->id]);
            });

        $this->info('End advertisements.');

        $this->info('Start companies.');

        DB::table('companies')
            ->where('is_top_at', '<', $dateNow)
            ->whereRaw('is_top_at != created_at')
            ->get()
            ->each(function ($company)  {
                $this->info($company->id);
                DB::update('update companies set is_top_at = ? where id = ?', [ $company->created_at, $company->id]);
            });

        $this->info('End companies.');

        return Command::SUCCESS;
    }
}
