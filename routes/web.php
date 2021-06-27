<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CardController;

use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\OrganizationCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\OrganizationTablesCrudController;
use App\Http\Controllers\Admin\OrganizationTableListCrudController;
use App\Http\Controllers\Admin\BookingCrudController;


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization,X-localization,X-No-Cache');

Route::get('/', [MainController::class, 'index'])->name('index');

Route::prefix('profile')->group(function() {
    Route::get('/',[MainController::class, 'profile'])->name('profile');
    Route::get('settings',[MainController::class, 'profileSettings'])->name('profile.settings');
    Route::get('payments',[MainController::class, 'profilePayments'])->name('profile.payments');
    Route::get('history',[MainController::class, 'profileHistory'])->name('profile.history');
});

Route::prefix('favorite')->group(function() {
    Route::get('/',[MainController::class, 'favorite'])->name('favorite');
});

Route::prefix('home')->group(function() {
    Route::get('/',[MainController::class, 'home'])->name('home');
    Route::get('/{id}',[MainController::class, 'home'])->name('home');
    Route::get('/restaurants',[MainController::class, 'homeRestaurants'])->name('home.restaurants');
    Route::get('/cafe',[MainController::class, 'homeCafe'])->name('home.cafe');
    Route::get('/bars',[MainController::class, 'homeBars'])->name('home.bars');
});

Route::prefix('top')->group(function() {
    Route::get('/',[MainController::class, 'top'])->name('top');
});

Route::prefix('contacts')->group(function() {
    Route::get('contracts',[ContactController::class,'contracts']);
    Route::get('privacy',[ContactController::class,'privacy']);
});

Route::get('users',[UserCrudController::class, 'list']);
Route::get('tables',[OrganizationTableListCrudController::class,'list']);
Route::get('tables/{id}',[OrganizationTableListCrudController::class,'getBySectionId']);
Route::get('organization',[OrganizationCrudController::class, 'list']);
Route::get('organizationTables',[OrganizationTablesCrudController::class, 'list']);
Route::get('organizationTables/{id}',[OrganizationTablesCrudController::class, 'getByOrganizationId']);
Route::get('lk/{id}',[LinkController::class, 'link']);

Route::get('/queue', function() {
    Artisan::call('queue:work');
    dd('queue work');
});

Route::get('exit',[LoginController::class, 'logout'])->name('exit');

Route::prefix('admin')->group(function () {
    //Route::get('login', [LoginController::class, 'showLoginForm'])->name('backpack.auth.register');
    Route::get('dashboard',[MainController::class, 'dashboard'])->name('dashboard');
    Route::get('dashboard/booking/{id}',[MainController::class, 'dashboardBooking'])->name('dashboard.booking');
    Route::get('register',[RegisterController::class, 'showRegistrationForm'])->name('backpack.auth.register');
    Route::post('register',[RegisterController::class, 'register'])->name('backpack.auth.register');
    Route::get('phone_verify',[UserController::class, 'phoneVerify'])->name('phone.verify');
    Route::post('phone_verify',[UserController::class, 'checkPhoneCode'])->name('phone.code');
    Route::get('blocked_user',[UserController::class, 'blockedUser'])->name('user.blocked');
    Route::get('blocked_restricted',[UserController::class, 'restrictedUser'])->name('user.restricted');
    Route::get('booking/status_date/{date}',[BookingCrudController::class, 'bookingStatus']);
    Route::get('booking/status/{id}',[BookingCrudController::class, 'cancel']);
});

Route::get('form/{bookingId}', [PaymentController::class,'form'])->name('payment.form');

Route::prefix('card')->group(function() {
    Route::get('success', [CardController::class,'success'])->name('success');
});
