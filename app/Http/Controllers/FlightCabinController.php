<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightCabin\CreateFlightCabinRequest;
use App\Http\Requests\FlightCabin\UpdateFlightCabinRequest;
use App\Models\FlightCabin;
use App\Services\FlightCabin\FlightCabinService;
use Illuminate\Http\Request;

class FlightCabinController extends Controller
{
    protected $service ;
    public function __construct(FlightCabinService $service){
        $this->service = $service ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',FlightCabin::class);
        $allFlightCabins = FlightCabin::with(['flight','bookings'])->paginate(10);
        return self::paginated($allFlightCabins);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFlightCabinRequest $request)
    {
        $flihgtCabin = $this->service->create($request->validated());
        return self::success([$flihgtCabin],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FlightCabin $flightCabin)
    {
        $this->authorize('view', $flightCabin);
        $flightCabin = $flightCabin->load(['flight','bookings']);
        return self::success([$flightCabin]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlightCabinRequest $request, FlightCabin $flightCabin)
    {
        $updatedFlightCabin = $this->service->update($request->validated(),$flightCabin);
        return self::success([$updatedFlightCabin]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlightCabin $flightCabin)
    {
        $this->authorize('delete',$flightCabin);
        $flightCabin->delete();
        return self::success([null]);
    }
}
