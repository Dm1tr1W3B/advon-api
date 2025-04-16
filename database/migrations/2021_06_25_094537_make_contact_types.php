<?php

use App\Models\ContactType;
use Illuminate\Database\Migrations\Migration;

class MakeContactTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $contact_types = [
            "instagram",
            "facebook",
        ];
        foreach ($contact_types as $contact_type)
            ContactType::create(["name" => $contact_type, "status" => true]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
