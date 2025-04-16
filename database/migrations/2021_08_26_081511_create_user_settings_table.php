<?php

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('is_hide_user')->default(false);
            $table->boolean('is_hide_company')->default(false);
            $table->boolean('is_receive_news')->default(false);
            $table->boolean('is_receive_messages_by_email')->default(false);
            $table->boolean('is_receive_comments_by_email')->default(false);
            $table->boolean('is_receive_price_favorite_by_email')->default(false);
            $table->boolean('is_receive_messages_by_phone')->default(false);
            $table->boolean('is_receive_comments_by_phone')->default(false);
            $table->boolean('is_receive_price_favorite_by_phone')->default(false);
            $table->timestamps();
        });

        User::withTrashed()
            ->get()
            ->each(function ($user) {
                UserSetting::create([
                    'user_id' => $user->id,
                    'is_hide_user' => false,
                    'is_hide_company' => false,
                    'is_receive_news' => false,
                    'is_receive_messages_by_email' => false,
                    'is_receive_comments_by_email' => false,
                    'is_receive_price_favorite_by_email' => false,
                    'is_receive_messages_by_phone' => false,
                    'is_receive_comments_by_phone' => false,
                    'is_receive_price_favorite_by_phone' => false,
                ]);

            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
