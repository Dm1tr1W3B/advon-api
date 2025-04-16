<?php

namespace App\Providers;

use App\FormFields\AdditionalPhotos;
use App\FormFields\CountryRegionCity;
use App\FormFields\HashTags;
use App\FormFields\ImageId;
use App\FormFields\Readable;
use App\Models\Advertisement;
use App\Models\Company;
use App\Models\Image;
use App\Models\Language;
use App\Models\Message;
use App\Models\User;
use App\Models\UserImage;
use App\Observers\CompanyObserver;
use App\Observers\ImageObserver;
use App\Observers\MessageObserver;
use App\Observers\UserImageObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(AdditionalPhotos::class);
        Voyager::addFormField(Readable::class);
        Voyager::addFormField(HashTags::class);
        Voyager::addFormField(CountryRegionCity::class);
        Voyager::addFormField(ImageId::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('languages'))
            config([
                'voyager.multilingual.locales' => array_merge(config('voyager.multilingual.locales'), Language::where('key', '!=', 'ru')->where('enabled', 1)->pluck('key')->values()->toArray())
            ]);
        Image::observe(ImageObserver::class);
        UserImage::observe(UserImageObserver::class);
        User::observe(UserObserver::class);
        Company::observe(CompanyObserver::class);
        Message::observe(MessageObserver::class);
        Paginator::useBootstrap();
        Voyager::addAction(\App\Actions\ModerateAction::class);


    }
}
