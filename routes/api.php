<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Models\Country;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public Routes (no authentication required)
Route::middleware(['throttle:api'])->group(function(){

    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('guest');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('guest');
});

// Protected Routes (requires API authentication)
Route::middleware('auth:api')->group(function(){
    Route::controller(AuthController::class)->group(function(){
        Route::get('/me','me');
        Route::post('/logout','logout');
        Route::post('complete-profile','completeProfile');
    });
    Route::resource('permission',PermissionController::class);
    Route::resource('role',RoleController::class);
    Route::get('/get-all-cities',[CityController::class,'index']);
    Route::get('/show-city/{city}',[CityController::class,'show']);

    Route::get('get-all-countries',[CountryController::class,'index']);
    Route::get('show-country/{country}',[CountryController::class,'show']);

    Route::get('get-all-airports',[AirportController::class,'index']);
    Route::get('show-airport/{airport}',[AirportController::class,'show']);

    Route::resource('hotel',HotelController::class);
});

//
Route::controller(EmailVerificationController::class)->middleware('auth:api')->group(function(){
    // Resend Email Verification Link
    Route::post('/email/verification-notification','resend')
    ->middleware(['throttle:api'])->name('verification.send');

    // Email verification callback route
    Route::get('/email/verify', 'notice')->name('verification.notice');

    //
    Route::get('/email/verify/{id}/{hash}', 'verify')
    ->middleware(['signed'])->name('verification.verify');
});

Route::controller(SocialAuthController::class)->group(function(){
    Route::get('/auth/google/redirect', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
Route::post('/auth/google/login', [AuthController::class, 'loginWithEmail'])->middleware('throttle:5,1');
