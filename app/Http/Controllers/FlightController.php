<?php

namespace App\Http\Controllers;

use App\Http\Requests\Flight\CreateFlightRequest;
use App\Http\Requests\Flight\FilterFlightRequest;
use App\Http\Requests\Flight\UpdateFlightRequest;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use App\Services\Flight\FlightService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    use ManageCache ;
    protected $flightService ;
    public function __construct(FlightService $flightService){
        $this->flightService = $flightService ;
    }
    /**
     * Display a paginated list of flights with optional filters and store it in cache for 20 minutes.
     * @param \App\Http\Requests\Flight\FilterFlightRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterFlightRequest $request)
    {
        $cacheKey = 'all_flights_' . md5(json_encode($request->validated() ?? 'all'));
        $allFlights = $this->getFromCache('flights' , $cacheKey)
        ?? $this->addToCache(
            'flights',
            $cacheKey ,
            $this->flightService->getAllFlights($request->validated()) ,
            1200);
        return self::paginated($allFlights , FlightResource::class);
    }

    /**
     * Store a new flight in the database and clear old cache.
     * @param \App\Http\Requests\Flight\CreateFlightRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateFlightRequest $request)
    {
        $flight = $this->flightService->createFlight($request->validated());
        $this->clearCache('flights');
        return self::success([$flight]);
    }

    /**
     * Display a specific flight by ID, including related airport data.
     * The result is cached for 20 minutes.
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Flight $flight)
    {
        $cacheKey = 'flight_' . $flight->id ;

        // Retrieve flight data from cache if it exists; otherwise, load it and cache the result.
        $flight = $this->getFromCache('flights',$cacheKey)
            ?? $this->addToCache(
                'flights' ,
                $cacheKey ,
                $flight->load(['departureAirport','arrivalAirport','flightDetails']) ,
                1200);
        return self::success([new FlightResource($flight)]);
    }

    /**
     * Update a specific flight with validated data and clear old cache.
     * @param \App\Http\Requests\Flight\UpdateFlightRequest $request
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $updatedFlight = $this->flightService->updateFlight($request->validated(),$flight);
        $this->clearCache('flights');
        return self::success([$updatedFlight]);
    }

    /**
     * Delete a specific flight from the database.
     * clear old cache.
     * @param \App\Models\Flight $flight
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Flight $flight)
    {
        $this->authorize('delete',$flight);
        $flight->delete();

        $this->clearCache('flights'); // clear cache.
        return self::success([null]);
    }
}
