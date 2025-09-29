<?php

namespace App\Traits;

use App\Models\HotelBooking;

trait LoadsHotelBookingData
{
    protected function loadBookingData($bookingId){
        $booking = HotelBooking::with([
            'user',
            'room',
        ])->findOrFail($bookingId);

        return $booking ;
    }
    public function transformBookingData($booking){
        $user = $booking->user ;
        $room = $booking->room ;
        return [
            'user_name'=>$user->name ,
            'room_details'=>[
                'Hotel Name'=>$room->hotel->name,
                'room_type'=>$room->room_type ,
                'price_per_night'=>$room->price_per_night ,
                'capacity'=>$room->capacity ,
            ],
            'check_in_date'=>$booking->check_in_date ,
            'check_out_date'=>$booking->check_out_date ,
            'total_price'=> $booking->getAmount() ,
            'status'=>$booking->status ,
        ];
    }

}
