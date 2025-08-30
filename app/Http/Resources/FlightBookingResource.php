<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'flight_cabins_id'=>$this->flight_cabins_id ,
            'booking_date'=>Carbon::parse($this->booking_date),
            'seat_number'=>$this->seat_number ,
            'status'=>$this->status ,
            'user_id'=>$this->user_id ,
            'flight_cabin'=>[
                    'id'=>$this->flightCabin->id,
                    'flight_id'=>$this->flightCabin->flight_id ,
                    'class_name'=>$this->flightCabin->class_name ,
                    'price'=>$this->flightCabin->price ,
                    'available_seats'=>$this->flightCabin->available_seats ,
                    'note'=>$this->flightCabin->note,
            ],

            'flight'=>[
                'id'=> $this->flightCabin->flight->id,
                'airline'=> $this->flightCabin->flight->airline,
                'flight_number'=> $this->flightCabin->flight->flight_number,
                'departure_airport_id'=> $this->flightCabin->flight->departure_airport_id,
                'arrival_airport_id'=> $this->flightCabin->flight->arrival_airport_id,
                'departure_time'=> $this->flightCabin->flight->departure_time,
                'arrival_time'=> $this->flightCabin->flight->arrival_time,
            ],

            'arrival_airport'=>[
                    "id"=>$this->flightCabin->flight->arrivalAirport->id,
                    "name"=> $this->flightCabin->flight->arrivalAirport->name,
                    "city_name"=> $this->flightCabin->flight->arrivalAirport->city->name,
                    "country_name"=> $this->flightCabin->flight->arrivalAirport->city->country->name,
                    "IATA_code"=> $this->flightCabin->flight->arrivalAirport->IATA_code,
            ],
            'departure_airport'=>[
                    "id"=>$this->flightCabin->flight->departureAirport->id,
                    "name"=> $this->flightCabin->flight->departureAirport->name,
                    "city_name"=> $this->flightCabin->flight->departureAirport->city->name,
                    "country_name"=> $this->flightCabin->flight->departureAirport->city->country->name,
                    "IATA_code"=> $this->flightCabin->flight->departureAirport->IATA_code,
            ],
        ];
    }
}
