<?php
namespace App\Services\FlightCabin;

use App\Models\FlightCabin;

class FlightCabinService{
    public function create(array $data){
        return FlightCabin::create($data);
    }
    public function update(array $data , FlightCabin $flightCabin){
        $flightCabin->update($data);
        return $flightCabin->refresh();
    }
}
