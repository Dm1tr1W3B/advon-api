<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ContactType;
use App\Http\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

class AddImageIdForContactTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_types', function (Blueprint $table) {
            $table->foreignId('image_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
        });

        ContactType::where('status', true)->delete();

        $contact_types = [
            "Telegram",
            "Viber",
            "Skype",
            "WhatsApp",
            "Instagram",
            "Facebook",
        ];

        foreach ($contact_types as $contact_type) {
            $path = Storage::path('/public/contacts/'. $contact_type . '.png');

            $image = ImageHelper::createPhotoFromURL('contacts', $path);

            if (!isset($image->id))
                continue;

            ContactType::create(["name" => $contact_type, "status" => true , "image_id" => $image->id]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_types', function (Blueprint $table) {
            $table->dropColumn('image_id');
        });
    }
}
