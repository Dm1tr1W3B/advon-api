<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Http\Helpers\ImageHelper;

class FixLogoIdForCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $oldCompanies = DB::connection('old_advon')
            ->table("bff_shops")
            ->distinct()
            ->get();

        Company::get()->each(function ($company) use ($oldCompanies) {

            $oldCompany = $oldCompanies->where('user_id', $company->owner_id)->first();
            if (empty($oldCompany) || empty($oldCompany->logo))
                return true;

            $oldLogoPath = 'https://advon.me/files/images/shop/logo/0/'. $oldCompany->id.'l' .$oldCompany->logo;

            $logo = ImageHelper::createPhotoFromURL('companies/old', $oldLogoPath);

            if (!isset($logo->id))
                return true;

            $company->logo_id = $logo->id;
            $company->save();

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
