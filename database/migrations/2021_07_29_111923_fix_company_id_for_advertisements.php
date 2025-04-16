<?php

use App\Models\Advertisement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixCompanyIdForAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $date = new DateTime('2021-06-05');
        Advertisement::whereNotNull('company_id')
            ->where('created_at', '<', $date)
            ->get()
            ->each(function ($advertisement) {
                $advertisement->user_id = $advertisement->company_id;

                try {
                    $advertisement->save();

                } catch (Exception $e)  {
                    // var_dump($advertisement->company_id);
                }


            });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
