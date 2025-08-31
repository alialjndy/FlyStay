<?php

namespace App\Http\Controllers;

use App\Http\Resources\FlightBookingResource;
use App\Http\Resources\HotelBookingResource;
use App\Services\Booking\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public $service ;
    public function __construct(BookingService $service){
        $this->service = $service ;
    }
    public function myBookings(){
        $data = $this->service->getUserBookings() ;
        return self::success([
            'hotel-bookings'=> HotelBookingResource::collection(($data['hotel_bookings'])) ,
            'flight-bookings'=>FlightBookingResource::collection(($data['flight_bookings'])) ,
        ]);
    }
}
