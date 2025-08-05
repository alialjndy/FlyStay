<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id ,
            'airline'=>$this->airline,
            'flight_number'=>$this->flight_number ,
            'departure_airport_id'=>$this->departure_airport_id ,
            'arrival_airport_id'=>$this->arrival_airport_id ,
            'departure_time'=>$this->departure_time ,
            'arrival_time'=>$this->arrival_time ,
            'departure_airport'=>$this->whenLoaded('departureAirport',function(){
                return [
                    'id'=>$this->departureAirport->id,
                    'name'=>$this->departureAirport->name,
                    'city_name'=>$this->departureAirport->city->name,
                    'IATA_code'=>$this->departureAirport->IATA_code
                ];
            }),
            'arrival_airport'=>$this->whenLoaded('arrivalAirport',function(){
                return [
                    'id'=>$this->arrivalAirport->id,
                    'name'=>$this->arrivalAirport->name,
                    'city_name'=>$this->arrivalAirport->city->name,
                    'IATA_code'=>$this->arrivalAirport->IATA_code
                ];
            }),
        ];
    }
}
