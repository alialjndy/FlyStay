<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\MockingController;
use App\Models\Hotel;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

Route::get('hotel-hack-xss' , function(){
    $hotels = Hotel::paginate(10);
    return view('Test_Hack.hotels-test-xss' , ['hotels' => $hotels]);
});
Route::get('rating-hack-xss' , function(){
    $ratings = Rating::all();
    return view('Test_Hack.ratings-test-xss' , ['ratings' => $ratings]);
});
Route::get('hotels/{id}' , function($id){
    $hotel = Hotel::findOrFail($id);
    return view('Test_Hack.hotel-details' , ['hotel' => $hotel]);
})->name('hotels.show');

Route::post('XSS', function (Request $r) {
    return 'transfer executed: ' . $r->input('amount');
});
Route::get('XSS', function (Request $r) {
    return view('Test.XSS');
});
Route::get('URL', function (Request $request){
    $url = action([HotelController::class , 'store'] , ['id' =>1]);
    return view('Test.URL', ['uri' => $url]);
});


Route::get('mock1',[MockingController::class , 'index']);
Route::post('mock2' , [MockingController::class , 'store']);
