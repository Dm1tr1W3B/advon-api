<?php

use App\Models\Currency;
use App\Models\Image;
use App\Models\PaymentSystem;
use App\Models\PaymentSystemCurrency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AddPsPayok extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newImage = new Image();
        $newImage->photo_url = 'payment_systems/payok.png';
        $newImage->real_image = Storage::get('/public/payment_systems/payok.png');
        $newImage->folder = 'payment_systems';

        $newImage->name = "original_payment_systems";
        $newImage->type = 'png';
        $newImage->width = 18;
        $newImage->height = 150;
        $newImage->device_type = "desktop";
        $newImage->save();

        PaymentSystem::create([
            'name' => 'payok',
            'prefix' => 'payok',
            'description' => '',
            'image_id' => $newImage->id,
            'is_active' => true,
        ]);

        $paymentSystem = PaymentSystem::where('prefix', 'payok')->first();
        $currency = Currency::where('code', 'RUB')->first();

        PaymentSystemCurrency::create([
            'payment_system_id' => $paymentSystem->id,
            'currency_id' => $currency->id,
        ]);

        PaymentSystem::where('prefix', 'robokassa')
            ->update(['is_active' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        PaymentSystem::where('prefix', 'robokassa')
            ->update(['is_active' => true]);

        PaymentSystem::where('prefix', 'payok')
            ->update(['is_active' => false]);
    }
}
