<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/google/redirect', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
Route::controller(EmailVerificationController::class)->group(function () {
    // Route::post('/email/verification-notification', 'resend')
    //     ->middleware(['auth:sanctum', 'throttle:6,1'])
    //     ->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', 'verify')
        ->middleware(['signed'])
        ->name('verification.verify');
});
