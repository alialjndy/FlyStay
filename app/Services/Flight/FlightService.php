<?php
namespace App\Services\Flight;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightService{
    /**
     * Retrieve all flights based on optional filters and paginate the results.
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllFlights(array $filters){
        return Flight::filter($filters)->with(['departureAirport','arrivalAirport'])->paginate(10);
    }
    /**
     * Create a new flight using the given data.
     * @param array $data
     * @return Flight
     */
    public function createFlight(array $data){
        return Flight::create($data);
    }
    /**
     * Update an existing flight with new data.
     * @param array $data
     * @param \App\Models\Flight $flight
     * @return Flight
     */
    public function updateFlight(array $data , Flight $flight){
        $flight->update($data);
        return $flight->refresh();
    }
}
