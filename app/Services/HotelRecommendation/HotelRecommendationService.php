<?php
namespace App\Services\HotelRecommendation;

use App\Models\FlightBooking;
use App\Models\Hotel;

class HotelRecommendationService{
    public function recommindationHotels(FlightBooking $flightBooking){
        $destinationCity = $flightBooking->destination_city ;
        $recommindationHotels = Hotel::where('city_id',$destinationCity->id)->get();
        return $recommindationHotels ;
    }
}
