<?php

use App\Models\Image;
use App\Models\PaymentSystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prefix');
            $table->text('description')->nullable()->default('');
            $table->foreignId('image_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('is_active');
            $table->timestamps();
        });

        $newImage = new Image();
        $newImage->photo_url = 'payment_systems/robokassa.png';
        $newImage->real_image = Storage::get('/public/payment_systems/robokassa.png');
        $newImage->folder = 'payment_systems';

        $newImage->name = "original_payment_systems";
        $newImage->type = 'png';
        $newImage->width = 18;
        $newImage->height = 150;
        $newImage->device_type = "desktop";
        $newImage->save();

        PaymentSystem::create([
            'name' => 'robokassa',
            'prefix' => 'robokassa',
            'description' => '',
            'image_id' => $newImage->id,
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_systems');
    }
}
