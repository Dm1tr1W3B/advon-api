<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UserPhoneKey;

class CreateUserPhoneKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_phone_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_phone')->index();
            $table->enum('type', [UserPhoneKey::RECOVERY, UserPhoneKey::USER_VERIFICATION])->index();
            $table->string('key', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_phone_keys');
    }
}
