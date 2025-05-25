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
