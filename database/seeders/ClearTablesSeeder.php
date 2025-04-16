<?php
namespace Database\Seeders;


use App\Models\Advertisement;
use App\Models\AdvertisementAuthorComplaint;
use App\Models\AdvertisementAuthorComplaintType;
use App\Models\AdvertisementComment;
use App\Models\AdvertisementComplaint;
use App\Models\AdvertisementComplaintType;
use App\Models\AdvertisementFavorite;
use App\Models\AdvertisementImage;
use App\Models\AdvertisementView;
use App\Models\Chat;
use App\Models\ChatMessageFile;
use App\Models\ChatMessageStatus;
use App\Models\ChatUser;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\CompanyImage;
use App\Models\CompanyPhone;
use App\Models\CompanySocial;
use App\Models\Deposit;
use App\Models\Image;
use App\Models\Message;
use App\Models\OfferYourPrice;
use App\Models\SocialUser;
use App\Models\Subscription;
use App\Models\TransactionBalance;
use App\Models\User;
use App\Models\UserCategory;
use App\Models\UserContact;
use App\Models\UserImage;
use App\Models\UserPhone;
use App\Models\UserReferral;
use App\Models\UserSetting;
use App\Models\UserSocial;
use Database\Seeders\OldData\CreateOldAdvertisementsSeeder;
use Database\Seeders\OldData\CreateOldCompaniesSeeder;
use Database\Seeders\OldData\CreateOldUsersSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Traits\Seedable;

class ClearTablesSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__ . '/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Clear Data Before Seeding
        //descending so that there are no errors due to relationship

        Deposit::truncate();
        Subscription::truncate();
        TransactionBalance::truncate();

        SocialUser::truncate();
        UserCategory::truncate();
        UserContact::truncate();
        UserPhone::truncate();
        UserReferral::truncate();
        UserSetting::truncate();
        UserSocial::truncate();

        OfferYourPrice::truncate();
        ChatMessageFile::truncate();
        ChatMessageStatus::truncate();
        Message::truncate();
        ChatUser::truncate();
        Chat::truncate();

        CompanyContact::truncate();

        CompanyPhone::truncate();
        CompanySocial::truncate();

        AdvertisementAuthorComplaintType::truncate();
        AdvertisementAuthorComplaint::truncate();
        AdvertisementComplaintType::truncate();
        AdvertisementComplaint::truncate();

        AdvertisementFavorite::truncate();
        AdvertisementComment::truncate();
        AdvertisementFavorite::truncate();
        AdvertisementView::truncate();


        Advertisement::withTrashed()->get()
            ->each(function ($model)  {

                $model->photo_id = null;
                $model->save();

                $imageIds = $model->images->map(function ($item) {
                    return $item->id;
                });

                if ($imageIds->isNotEmpty())
                    Image::destroy($imageIds);

            });

        Company::withTrashed()->get()
            ->each(function ($model)  {

                $model->photo_id = null;
                $logoId = $model->logo_id;
                $model->logo_id = null;
                $model->save();

                $imageIds = $model->images->map(function ($item) {
                    return $item->id;
                });

                if ($imageIds->isNotEmpty())
                    Image::destroy($imageIds);

                if (!empty($logoId))
                    Image::destroy($logoId);

            });

        User::withTrashed()->get()
            ->each(function ($model)  {

                $imageIds = $model->images->map(function ($item) {
                    return $item->id;
                });

                if ($imageIds->isNotEmpty())
                    Image::destroy($imageIds);

            });




        Schema::disableForeignKeyConstraints();
        Advertisement::truncate();
        Company::truncate();
        User::truncate();

        Schema::enableForeignKeyConstraints();



        DB::table('email_verification_codes')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('user_roles')->truncate();


        //Setting::truncate();
        //DB::table('permission_role')->truncate();
        //<-----End Clear------>

    }
}
