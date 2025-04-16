<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use Illuminate\Console\Command;
use DateTime;

class MonthlyUpdateAdvertisementPublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:monthly_update_published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command monthly update advertisement published';

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
        $this->info('Start update.');
        $date = new DateTime('2021-11-13');
        $publishedAt = new DateTime('NOW');

        // old
        Advertisement::where('created_at', '<', $date)
            ->update([
                'is_published' => true,
                'published_at' => $publishedAt
            ]);

        // new
        Advertisement::where('created_at', '<', $date)
            ->update([
                'is_published' => true,
                'published_at' => $publishedAt
            ]);

        $this->info('Finish update.');
        return 0;
    }
}
