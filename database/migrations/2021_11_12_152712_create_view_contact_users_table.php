<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\ViewContactUser;

class CreateViewContactUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_contact_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->integer('view_contact')
                ->default(5)
                ->comment('остаток количество показов контактов');
            $table->timestamps();
        });

        User::withTrashed()
            ->get()
            ->each(function ($user) {
                ViewContactUser::create([
                    'user_id' => $user->id,
                    'view_contact' => 5,
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
        Schema::dropIfExists('view_contact_users');
    }
}
