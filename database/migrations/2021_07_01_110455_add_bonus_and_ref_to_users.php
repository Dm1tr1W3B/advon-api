<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusAndRefToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->double("bonus_balance")->nullable();
            $table->string("ref_code")->unique()->nullable();
        });

        $users = User::all();
        foreach ($users as $user) {
            do {
                $token = \Illuminate\Support\Str::random(User::$lengthOfRefCode);
            } while (User::where('ref_code', $token)->first());
            $user->ref_code = $token;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(["bonus_balance", "ref_code"]);
        });
    }
}
