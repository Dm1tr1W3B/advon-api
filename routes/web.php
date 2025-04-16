<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\UpdateContentController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Route::get('/countries', [UpdateContentController::class, 'countries'])
        ->name('admin.getcountries');

    Route::get('/regions', [UpdateContentController::class, 'regions'])
        ->name('admin.getregions');

    Route::get('/cities', [UpdateContentController::class, 'cities'])
        ->name('admin.getcities');

    Route::get('updateContent', [UpdateContentController::class, 'updateContent']);

    Route::get('/users-change-balance/{id}', [UserController::class, 'changeBalance'])
        ->middleware(['auth'])
        ->name('users.change.balance');
    Route::post('/users-update-balance', [UserController::class, 'updateBalance'])
        ->middleware(['auth'])
        ->name('users.update.balance');

    Route::get('/users-block/{id}', [UserController::class, 'changeBlock'])
        ->middleware(['auth'])
        ->name('users.block');

    Route::get('/advertisements-moderate/{id}', [AdvertisementController::class, 'moderate'])
        ->middleware(['auth'])
        ->name('voyager.advertisements.moderate');


    Voyager::routes();
});
