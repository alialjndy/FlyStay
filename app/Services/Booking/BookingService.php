<?php
namespace App\Services\Booking;

use App\Models\FlightBooking;
use App\Models\HotelBooking;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookingService{
    public function getUserBookings(){
        $user = JWTAuth::parseToken()->authenticate();
        $hotelBooking = HotelBooking::where('user_id',$user->id)->get();
        $flightBooking = FlightBooking::where('user_id',$user->id)->get();

        return [
            'hotel-bookings'=>$hotelBooking,
            'flight-bookings'=>$flightBooking,
        ];
    }
}
