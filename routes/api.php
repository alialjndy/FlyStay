<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public Routes (no authentication required)
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])
    ->name('login')
    ->middleware(['throttle:6,1']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

// Protected Routes (requires API authentication)
Route::middleware('auth:api')->group(function(){
    Route::get('/me',[AuthController::class,'me']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('complete-profile',[AuthController::class,'completeProfile']);
});

//
Route::controller(EmailVerificationController::class)->middleware('auth:api')->group(function(){
    // Resend Email Verification Link
    Route::post('/email/verification-notification','resend')
    ->middleware(['throttle:6,1'])->name('verification.send');

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


