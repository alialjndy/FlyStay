<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(SocialAuthController::class)->withoutMiddleware('verified')->group(function () {
    Route::get('/auth/google/redirect', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
Route::controller(EmailVerificationController::class)->withoutMiddleware('verified')->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'verify')
        ->middleware(['signed'])
        ->name('verification.verify');
});
Route::get('payment-test',function(){
    return view('payment-test');
});
