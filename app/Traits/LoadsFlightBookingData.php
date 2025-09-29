<?php

namespace App\Traits;

use App\Models\FlightBooking;
use App\Models\Payment;
use Carbon\Carbon;

trait LoadsFlightBookingData
{
    public function loadFlightBookingData(string $bookingId){
        $booking = FlightBooking::with([
            'user',
            'flightCabin.flight.departureAirport.city',
            'flightCabin.flight.departureAirport.country',
            'flightCabin.flight.arrivalAirport.city',
            'flightCabin.flight.arrivalAirport.country',
        ])->findOrFail($bookingId);

        return $booking ;
    }

    public function transformBookingData($booking){
        $flight = $booking->flightCabin->flight ;
        $bookingData = [
            'user_name'     => $booking->user->name ,
            'flightCabin'   => $booking->flightCabin->class_name ,
            'flight_number' => $flight->flight_number,
            'departure'     => $flight->departureAirport->city->name . ', ' . $flight->departureAirport->country->name,
            'arrival'       => $flight->arrivalAirport->city->name . ', ' . $flight->arrivalAirport->country->name,
            'booking_date'  => $booking->booking_date,
            'seat_number'   =>$booking->seat_number ,
            'status'        =>$booking->status ,
        ];
        return $bookingData ;
    }
}
