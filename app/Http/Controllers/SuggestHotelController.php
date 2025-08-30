<?php

namespace App\Http\Controllers;

use App\Models\FlightBooking;
use App\Services\HotelRecommendation\HotelRecommendationService;
use Illuminate\Http\Request;

class SuggestHotelController extends Controller
{
    protected $service ;
    public function __construct(HotelRecommendationService $service){
        $this->service = $service ;
    }
    public function suggestHotels(FlightBooking $flightBooking){
        $hotels = $this->service->recommindationHotels($flightBooking);
        return self::success([$hotels]);
    }
}
