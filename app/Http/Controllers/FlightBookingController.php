<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightBooking\CreateFlightBookingRequest;
use App\Http\Requests\FlightBooking\FilterFlightBookingRequest;
use App\Http\Requests\FlightBooking\UpdateFlightBookingRequest;
use App\Models\FlightBooking;
use App\Models\User;
use App\Services\FlightBooking\FlightBookingService;
use Illuminate\Http\Request;

class FlightBookingController extends Controller
{
    protected $service ;
    public function __construct(FlightBookingService $service){
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(FilterFlightBookingRequest $request)
    {
        $this->authorize('viewAny',FlightBooking::class);
        $allFlightBookings = $this->service->getAllFlightBookings($request->validated());
        return self::paginated($allFlightBookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFlightBookingRequest $request)
    {
        $info = $this->service->createBooking($request->validated());
        return $info['status'] == 'success' ?
        self::success([$info['data']],201 , $info['message']) :
        self::error('Error Ouccred',$info['status'],$info['status_code'],[$info['message']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FlightBooking $flightBooking)
    {
        $this->authorize('view',$flightBooking);
        $flightBooking = $flightBooking->load(['user','flightCabin']);
        return self::success([$flightBooking]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlightBookingRequest $request, FlightBooking $flightBooking)
    {
        $info = $this->service->update($request->validated() , $flightBooking);
        return $info['status'] == 'success' ?
        self::success([$info['data']]) :
        self::error('Error Occurred','error',400 ,[$info['message']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlightBooking $flightBooking)
    {
        $this->authorize('delete',$flightBooking);
        $this->service->deleteBooking($flightBooking);
        return self::success([null]);
    }
    public function cancelBooking(FlightBooking $flightBooking){
        // $flightBooking = FlightBooking::findOrFail($id);
        $info = $this->service->cancelBooking($flightBooking);
        return $info['status'] == 'success' ?
            self::success([$info['data']],200 ,'Flight Cancelled Successfully!'):
            self::error('Error Occurred',$info['status'],400,[$info['message']]);
    }
    // public function test(){
    //     $flightAgentUser = User::create([
    //         'name'=>'flightAgent',
    //         'email'=>'flightAgent@gmail.com',
    //         'phone_number'=>'000000001',
    //         'password'=>bcrypt('strongPass123@'),
    //     ]);
    //     $flightAgentUser->assignRole('flight_agent');
    // }
}
