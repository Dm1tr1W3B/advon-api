<?php


use App\Http\Middleware\CheckMyCompany;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['set.locale'], 'prefix' => 'v1'], function () {

    /** Social */
    Route::post('/auth/{driver}/callback','App\\Http\\Controllers\\Api\\AuthApiController@handleCallback');

    Route::get('/getSocialLogins','App\\Http\\Controllers\\Api\\CommonApiController@getSocialLogins');
    Route::post('/createEmailForSocialUser','App\\Http\\Controllers\\Api\\AuthApiController@createEmailForSocialUser');


    Route::post('/storeUser', 'App\\Http\\Controllers\\Api\\UserApiController@storeUser');
    Route::post('/login', 'App\\Http\\Controllers\\Api\\AuthApiController@login');

    Route::get('/getCountries', 'App\\Http\\Controllers\\Api\\CountriesApiController@getCountries');
    Route::get('/getRegions', 'App\\Http\\Controllers\\Api\\CountriesApiController@getRegions');
    Route::get('/getCities', 'App\\Http\\Controllers\\Api\\CountriesApiController@getCities');
    Route::get('/getRegionsGrouped', 'App\\Http\\Controllers\\Api\\CountriesApiController@getRegionsGrouped');
    Route::get('/getCitiesGrouped', 'App\\Http\\Controllers\\Api\\CountriesApiController@getCitiesGrouped');
    Route::get('/getCountryAndCity', 'App\\Http\\Controllers\\Api\\CountriesApiController@getCountryAndCity');


    Route::get('/getCategoryList', 'App\\Http\\Controllers\\Api\\CategoryApiController@getCategoryList');
    Route::get('/getCategoryListForAdvertisement', 'App\\Http\\Controllers\\Api\\CategoryApiController@getCategoryListForAdvertisement');
    Route::get('/getChildCategoryList', 'App\\Http\\Controllers\\Api\\CategoryApiController@getChildCategoryList');
    Route::get('/getCategoryFormFields', 'App\\Http\\Controllers\\Api\\CategoryApiController@getCategoryFormFields');

    Route::get('/getLanguages', 'App\\Http\\Controllers\\Api\\LanguageApiController@getLanguages');
    Route::get('/getTranslations', 'App\\Http\\Controllers\\Api\\LanguageApiController@getTranslations');

    Route::get('/getAdvertisementListForGuest', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementListForGuest');
    Route::get('/getCompanyLogoListForGuest', 'App\\Http\\Controllers\\Api\\CompanyApiController@getCompanyLogoListForGuest');
    Route::get('/getCompanyListForGuest', 'App\\Http\\Controllers\\Api\\CompanyApiController@getCompanyListForGuest');
    Route::get('/getCompanyForGuest', 'App\\Http\\Controllers\\Api\\CompanyApiController@getCompanyForGuest');

    Route::get('/getUserForGuest', 'App\\Http\\Controllers\\Api\\UserApiController@getUserForGuest');

    /**
     * Common
     */
    Route::get('/getActiveCurrencies', 'App\\Http\\Controllers\\Api\\PaymentSystemApiController@getActiveCurrencies');
    Route::get('/getContacts', 'App\\Http\\Controllers\\Api\\CommonApiController@getContacts');
    Route::get('/getSocial', 'App\\Http\\Controllers\\Api\\CommonApiController@getSocial');
    Route::get('/getPopularHashtags', 'App\\Http\\Controllers\\Api\\CommonApiController@getPopularHashtags');
    Route::get('/getPages', 'App\\Http\\Controllers\\Api\\CommonApiController@getPages');
    Route::get('/getBanners', 'App\\Http\\Controllers\\Api\\CommonApiController@getBanners');
    Route::get('/getSeo', 'App\\Http\\Controllers\\Api\\CommonApiController@getSeo');

    Route::get('/getSMSBalance', 'App\\Http\\Controllers\\Api\\CommonApiController@getSMSBalance');


    Route::get('/showAdvertisement', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@showAdvertisement');
    Route::get('/getLastAdvertisements', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getLastAdvertisements');
    Route::get('/getIntersectAdvertisements', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getIntersectAdvertisements');
    Route::get('/getAuthorAllAdvertisements', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAuthorAllAdvertisements');
    Route::get('/getStatisticsForAdvertisement', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getStatisticsForAdvertisement');
    Route::get('/getAdvertisementsByCategory', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementsByCategory');
    Route::get('/getAdvertisementsByLocation', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementsByLocation');
    Route::get('/getAdvertisementsBySearch', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementsBySearch');
    Route::get('/getAdvertisementsTop', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementsTop');

    Route::get('/getAdvertisementCommentList', 'App\\Http\\Controllers\\Api\\AdvertisementCommentApiController@getAdvertisementCommentList');
    Route::get('/getComplaintTypeList', 'App\\Http\\Controllers\\Api\\ComplaintApiController@getComplaintTypeList');

    Route::get('/getPaymentList', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getPaymentList');

    Route::post('/userForgotPassword', 'App\\Http\\Controllers\\Api\\UserApiController@userForgotPassword');
    Route::post('/userRestorePassword', 'App\\Http\\Controllers\\Api\\UserApiController@userRestorePassword');

    /*
     * Feedback
     */
    Route::get('/getFeedbackTypeList', 'App\\Http\\Controllers\\Api\\FeedbackApiController@getFeedbackTypeList');
    Route::post('/storeFeedback', 'App\\Http\\Controllers\\Api\\FeedbackApiController@storeFeedback');
    Route::post('/feedbackSendEmail', 'App\\Http\\Controllers\\Api\\FeedbackApiController@feedbackSendEmail');

    Route::get('/deleteSubscriptionByEmail', 'App\\Http\\Controllers\\Api\\SubscriptionApiController@deleteSubscriptionByEmail');

    Route::group(['middleware' => ['jwt.auth']], function () {

        Route::post('/refresh', 'App\\Http\\Controllers\\Api\\AuthApiController@refresh');
        Route::post('/logout', 'App\\Http\\Controllers\\Api\\AuthApiController@logout');

    });

    Route::group(['middleware' => ['jwt.auth', 'check.role:user']], function () {
        Route::get('/getProfile', 'App\\Http\\Controllers\\Api\\UserApiController@getProfile');
        Route::post('/editProfile', 'App\\Http\\Controllers\\Api\\UserApiController@editProfile');
        Route::post('/uploadProfileAdditionalPhotos', 'App\\Http\\Controllers\\Api\\UserApiController@uploadProfileAdditionalPhotos');
        Route::post('/uploadProfileAvatar', 'App\\Http\\Controllers\\Api\\UserApiController@uploadProfileAvatar');
        Route::delete('/deleteProfileAdditionalPhoto', 'App\\Http\\Controllers\\Api\\UserApiController@deleteProfileAdditionalPhoto');
        Route::delete('/deleteProfileAvatar', 'App\\Http\\Controllers\\Api\\UserApiController@deleteProfileAvatar');

        Route::post('/changePassword', 'App\\Http\\Controllers\\Api\\UserApiController@changePassword');
        Route::post('/deleteAccount', 'App\\Http\\Controllers\\Api\\UserApiController@deleteAccount');
        Route::post('/updatePhone', 'App\\Http\\Controllers\\Api\\UserApiController@updatePhone');

        // User Settings
        Route::get('/getUserSettings', 'App\\Http\\Controllers\\Api\\UserApiController@getUserSettings');
        Route::get('/getUserSocialConnections','App\\Http\\Controllers\\Api\\UserApiController@getUserSocialConnections');
        Route::post('/social/{driver}/connect','App\\Http\\Controllers\\Api\\UserApiController@handleSocialConnectionCallback');
        Route::patch('/setHideUser','App\\Http\\Controllers\\Api\\UserApiController@setHideUser');
        Route::patch('/setHideCompany','App\\Http\\Controllers\\Api\\UserApiController@setHideCompany');
        Route::patch('/setReceiveNews','App\\Http\\Controllers\\Api\\UserApiController@setReceiveNews');
        Route::patch('/setReceiveMessagesByEmail','App\\Http\\Controllers\\Api\\UserApiController@setReceiveMessagesByEmail');
        Route::patch('/setReceiveCommentsByEmail','App\\Http\\Controllers\\Api\\UserApiController@setReceiveCommentsByEmail');
        Route::patch('/setReceivePriceFavoriteByEmail','App\\Http\\Controllers\\Api\\UserApiController@setReceivePriceFavoriteByEmail');
        Route::patch('/setReceiveMessagesByPhone','App\\Http\\Controllers\\Api\\UserApiController@setReceiveMessagesByPhone');
        Route::patch('/setReceiveCommentsByPhone','App\\Http\\Controllers\\Api\\UserApiController@setReceiveCommentsByPhone');
        Route::patch('/setReceivePriceFavoriteByPhone','App\\Http\\Controllers\\Api\\UserApiController@setReceivePriceFavoriteByPhone');



        Route::post('/transferBonusToBalance', 'App\\Http\\Controllers\\Api\\UserApiController@transferBonusToBalance');
        Route::get('/getRef', 'App\\Http\\Controllers\\Api\\UserApiController@getRef');

        Route::get('/getAdvertisementLimit', 'App\\Http\\Controllers\\Api\\UserApiController@getAdvertisementLimit');

        Route::get('/getChats', 'App\\Http\\Controllers\\Api\\ChatApiController@getChats');
        //Route::get('/getChatsPrivate', 'App\\Http\\Controllers\\Api\\ChatApiController@getChatsPrivate');
        //Route::get('/getChatsCompany', 'App\\Http\\Controllers\\Api\\ChatApiController@getChatsCompany');
        //Route::get('/getChatsFavorite', 'App\\Http\\Controllers\\Api\\ChatApiController@getChatsFavorite');
        //Route::get('/getChatsBlocked', 'App\\Http\\Controllers\\Api\\ChatApiController@getChatsBlocked');
        Route::get('/getMessages', 'App\\Http\\Controllers\\Api\\ChatApiController@getMessages');
        Route::delete('/deleteChat', 'App\\Http\\Controllers\\Api\\ChatApiController@deleteChat');
        Route::post('/turnChatFavorite', 'App\\Http\\Controllers\\Api\\ChatApiController@turnChatFavorite');
        Route::post('/turnBlockChat', 'App\\Http\\Controllers\\Api\\ChatApiController@turnBlockChat');

        Route::post('/sendMessage', 'App\\Http\\Controllers\\Api\\ChatApiController@sendMessage');
        Route::post('/offerYourPrice', 'App\\Http\\Controllers\\Api\\ChatApiController@offerYourPrice');



        Route::post('/storeCompany', 'App\\Http\\Controllers\\Api\\CompanyApiController@storeCompany');
        Route::middleware([CheckMyCompany::class])->group(function () {
            Route::get('/getCompany', 'App\\Http\\Controllers\\Api\\CompanyApiController@getCompany');
            Route::post('/editCompany', 'App\\Http\\Controllers\\Api\\CompanyApiController@editCompany');
            Route::post('/uploadCompanyMainPhoto', 'App\\Http\\Controllers\\Api\\CompanyApiController@uploadCompanyMainPhoto');
            Route::post('/uploadCompanyAdditionalPhotos', 'App\\Http\\Controllers\\Api\\CompanyApiController@uploadCompanyAdditionalPhotos');
            Route::delete('/deleteCompanyMainPhoto', 'App\\Http\\Controllers\\Api\\CompanyApiController@deleteCompanyMainPhoto');
            Route::delete('/deleteCompanyAdditionalPhoto', 'App\\Http\\Controllers\\Api\\CompanyApiController@deleteCompanyAdditionalPhoto');
            Route::delete('/deleteCompany', 'App\\Http\\Controllers\\Api\\CompanyApiController@deleteCompany');

        });

        Route::post('/userCheckSmsKey', 'App\\Http\\Controllers\\Api\\CommonApiController@userCheckSmsKey');
        Route::post('/userSendSmsKey', 'App\\Http\\Controllers\\Api\\CommonApiController@userSendSmsKey');

        Route::post('/storeAdvertisement', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@storeAdvertisement');
        Route::get('/editAdvertisement', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@editAdvertisement');
        Route::post( '/updateAdvertisement','App\\Http\\Controllers\\Api\\AdvertisementApiController@updateAdvertisement');
        Route::delete( '/deleteAdvertisement','App\\Http\\Controllers\\Api\\AdvertisementApiController@deleteAdvertisement');
        Route::get('/getAdvertisementListForUser', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getAdvertisementListForUser');
        Route::patch('/setPublished','App\\Http\\Controllers\\Api\\AdvertisementApiController@setPublished');
        Route::patch('/setHide','App\\Http\\Controllers\\Api\\AdvertisementApiController@setHide');
        Route::get('/getPersonContacts', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@getPersonContacts');
        Route::delete('/deleteAdvertisementAdditionalPhoto', 'App\\Http\\Controllers\\Api\\AdvertisementApiController@deleteAdvertisementAdditionalPhoto');

        Route::post('/addAdvertisementFavorite', 'App\\Http\\Controllers\\Api\\AdvertisementFavoriteApiController@addAdvertisementFavorite');
        Route::get('/getAdvertisementFavoriteList', 'App\\Http\\Controllers\\Api\\AdvertisementFavoriteApiController@getAdvertisementFavoriteList');
        Route::delete('/deleteAdvertisementsFavorite', 'App\\Http\\Controllers\\Api\\AdvertisementFavoriteApiController@deleteAdvertisementsFavorite');

        Route::post('/storeAdvertisementComment', 'App\\Http\\Controllers\\Api\\AdvertisementCommentApiController@storeAdvertisementComment');
        Route::post('/storeAdvertisementComplaint', 'App\\Http\\Controllers\\Api\\ComplaintApiController@storeAdvertisementComplaint');
        Route::post('/storeAdvertisementAuthorComplaint', 'App\\Http\\Controllers\\Api\\ComplaintApiController@storeAdvertisementAuthorComplaint');

        Route::get('/getSubscriptionList', 'App\\Http\\Controllers\\Api\\SubscriptionApiController@getSubscriptionList');
        Route::post('/storeSubscription', 'App\\Http\\Controllers\\Api\\SubscriptionApiController@storeSubscription');
        Route::post('/deleteSubscriptionForUser', 'App\\Http\\Controllers\\Api\\SubscriptionApiController@deleteSubscriptionForUser');
        Route::delete('/deleteSubscription', 'App\\Http\\Controllers\\Api\\SubscriptionApiController@deleteSubscription');

        Route::post('/sendEmailVerificationNotification', 'App\\Http\\Controllers\\Api\\UserApiController@sendEmailVerificationNotification');
        Route::post('/checkEmailVerificationCode', 'App\\Http\\Controllers\\Api\\UserApiController@checkEmailVerificationCode');

        Route::get('/getPaymentSystems', 'App\\Http\\Controllers\\Api\\PaymentSystemApiController@getPaymentSystems');
        Route::get('/getRedirectUrl', 'App\\Http\\Controllers\\Api\\PaymentSystemApiController@getRedirectUrl');

        // TransactionBalance
        Route::post('/setCompanyTop', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setCompanyTop');
        Route::post('/setCompanyAllocate', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setCompanyAllocate');
        Route::post('/setAdvertisementTopCountry', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setAdvertisementTopCountry');
        Route::post('/setAdvertisementAllocate', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setAdvertisementAllocate');
        Route::post('/setAdvertisementUrgent', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setAdvertisementUrgent');
        Route::post('/setAdvertisementTurbo', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@setAdvertisementTurbo');
        Route::get('/getAdvertisementPrices', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@getAdvertisementPrices');
        Route::post('/increaseLimitAdvertisementCategory', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@increaseLimitAdvertisementCategory');
        Route::get('/getTransactionBalanceForUser', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@getTransactionBalanceForUser');
        Route::get('/getViewContactUserPrices', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@getViewContactUserPrices');
        Route::get('/getViewContactUser', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@getViewContactUser');
        Route::post('/increaseLimitViewContactUser', 'App\\Http\\Controllers\\Api\\TransactionBalanceApiController@increaseLimitViewContactUser');

    });


    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect(env('FRONT_URL', 'https://advon.test.ut.in.ua') . '/registration/thanks-email');
    })->name('verification.verify');

});

/**
 * payment system callback
 */
Route::group(['prefix' => 'v1'], function () {
    Route::get('/notify', 'App\\Http\\Controllers\\Callback\\RobokassaController@notify');
    Route::get('/success', 'App\\Http\\Controllers\\Callback\\RobokassaController@success');
    Route::get('/fail', 'App\\Http\\Controllers\\Callback\\RobokassaController@fail');
    Route::post('/payok/callback', 'App\\Http\\Controllers\\Callback\\PayokController@callback');
});
