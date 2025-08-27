<?php

namespace App\Http\Controllers;

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
        return self::success(['hotel-bookings'=>$data['hotel-bookings'] ,'flight-bookings'=>$data['flight-bookings']]);
    }
}
