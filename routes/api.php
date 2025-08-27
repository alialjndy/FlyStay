<?php

use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\FlightCabinController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HotelBookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StripePaymentController;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

// Public Routes (no authentication required)
Route::middleware(['throttle:api'])->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('guest');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->middleware('guest');
});

// Protected Routes (requires API authentication)
Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/me', 'me');
        Route::post('/logout', 'logout');
        Route::post('complete-profile', 'completeProfile');
    });
    Route::resource('user', AdminUserController::class);

    Route::resource('permission', PermissionController::class);
    Route::post('assign-permission-to-user', [AdminPermissionController::class, 'assignPermissionToUser']);
    Route::post('assign-permission-to-role', [AdminPermissionController::class, 'assignPermissionToRole']);
    Route::post('remove-permission-from-user', [AdminPermissionController::class, 'removePermissionFromUser']);
    Route::post('remove-permission-from-role', [AdminPermissionController::class, 'removePermissionFromRole']);

    //
    Route::resource('role', RoleController::class);
    Route::post('assign-role', [AdminRoleController::class, 'assignRoleToUser']);
    Route::post('remove-role', [AdminRoleController::class, 'removeRoleFromUser']);


    Route::get('/get-all-cities', [CityController::class, 'index']);
    Route::get('/show-city/{city}', [CityController::class, 'show']);

    Route::get('get-all-countries', [CountryController::class, 'index']);
    Route::get('show-country/{country}', [CountryController::class, 'show']);

    Route::get('get-all-airports', [AirportController::class, 'index']);
    Route::get('show-airport/{airport}', [AirportController::class, 'show']);

    Route::put('hotels/{hotel}/update-with-photo', [HotelController::class, 'update']);
    Route::resource('hotel', HotelController::class);

    Route::resource('room', RoomController::class);
    Route::put('rooms/{room}/update-with-photo', [RoomController::class, 'update']);

    Route::resource('flight', FlightController::class);

    Route::resource('flight-cabin', FlightCabinController::class);

    Route::resource('flight-bookings', FlightBookingController::class);
    Route::post('flight-bookings/{flightBooking}/cancel', [FlightBookingController::class, 'cancelBooking']);
    // Route::post('flight-bookings/{flightBooking}/paid',[StripePaymentController::class,'createPaymentIntent']);

    Route::resource('hotel-bookings', HotelBookingController::class);
    Route::post('hotel-bookings/{hotelBooking}/cancel', [HotelBookingController::class, 'cancel']);

    Route::get('my-bookings',[BookingController::class,'myBookings']);

    // Route::get('/all-payments',[PaymentController::class ,'index']);
    // Route::get('/show-payment/{payment}',[PaymentController::class ,'show']);
    Route::resource('payments',PaymentController::class);
    Route::post('payments/{type}/{id}', [StripePaymentController::class, 'createPaymentIntent']);

    Route::post('favorite/{type}/{id}', [FavoriteController::class, 'handle']);
});
Route::post('stripe/webhook', [StripePaymentController::class, 'handleWebhook']);

//
Route::controller(EmailVerificationController::class)->middleware('auth:api')->group(function () {
    // Resend Email Verification Link
    Route::post('/email/verification-notification', 'resend')
        ->middleware(['throttle:api'])->name('verification.send');

    // Email verification callback route
    Route::get('/email/verify', 'notice')->name('verification.notice');

    //
    Route::get('/email/verify/{id}/{hash}', 'verify')
        ->middleware(['signed'])->name('verification.verify');
});

Route::controller(SocialAuthController::class)->group(function () {
    Route::get('/auth/google/redirect', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
});
// Route::post('/auth/google/login', [AuthController::class, 'loginWithEmail'])->middleware('throttle:5,1');

