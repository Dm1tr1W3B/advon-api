<?php

use App\Models\Company;
use App\Models\Image;
use App\Http\Helpers\ImageHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLogoIdForCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->bigInteger('logo_id')->nullable();
            $table->foreign('logo_id')->references('id')->on('images');
        });

        $oldCompanies = DB::connection('old_advon')
            ->table("bff_shops")
            ->distinct()
            ->get();

        Company::get()->each(function ($company) use ($oldCompanies) {

            $oldCompany = $oldCompanies->where('id', $company->id)->first();
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('logo_id');
        });
    }
}
