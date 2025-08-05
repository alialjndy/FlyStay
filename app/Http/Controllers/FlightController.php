<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flight\CreateFlightRequest;
use App\Http\Requests\Flight\FilterFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use App\Services\Flight\FlightService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected $flightService ;
    public function __construct(FlightService $flightService){
        $this->flightService = $flightService ;
    }
    /**
     * Display a paginated list of flights with optional filters.
     * @param \App\Http\Requests\Flight\FilterFlightRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterFlightRequest $request)
    {
        $allFlights = $this->flightService->getAllFlights($request->validated());
        return self::paginated($allFlights , FlightResource::class);
    }

    /**
     * Store a new flight in the database.
     * @param \App\Http\Requests\Flight\CreateFlightRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateFlightRequest $request)
    {
        $flight = $this->flightService->createFlight($request->validated());
        return self::success([$flight]);
    }

    /**
     * Display a specific flight by ID, including related airport data.
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Flight $flight)
    {
        $flight = $flight->load(['departureAirport','arrivalAirport','flightDetails']);
        return self::success([new FlightResource($flight)]);
    }

    /**
     * Update a specific flight with validated data.
     * @param \App\Http\Requests\Flight\UpdateFlightRequest $request
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $updatedFlight = $this->flightService->updateFlight($request->validated(),$flight);
        return self::success([$updatedFlight]);
    }

    /**
     * Delete a specific flight from the database.
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Flight $flight)
    {
        $this->authorize('delete',$flight);
        $flight->delete();
        return self::success([null]);
    }
}
