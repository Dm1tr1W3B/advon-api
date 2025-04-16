<?php
namespace Database\Seeders;


use App\Models\ContactType;
use Illuminate\Database\Seeder;


class CreateContactTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact_type = ContactType::firstOrNew([
            'name' => 'telegram'
        ]);

            $contact_type->fill([
                    'status' => 1
                ]
            )->save();

        $contact_type = ContactType::firstOrNew([
            'name' => 'viber'
        ]);

            $contact_type->fill([
                    'status' => 1
                ]
            )->save();

        $contact_type = ContactType::firstOrNew([
            'name' => 'skype'
        ]);

            $contact_type->fill([
                    'status' => 1
                ]
            )->save();

        $contact_type = ContactType::firstOrNew([
            'name' => 'whatsApp'
        ]);

            $contact_type->fill([
                    'status' => 1
                ]
            )->save();

    }
}
