<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelBooking\CreateHotelBookingRequest;
use App\Http\Requests\HotelBooking\FilterHotelBookingRequest;
use App\Http\Requests\HotelBooking\UpdateHotelBookingRequest;
use App\Http\Resources\HotelBookingResource;
use App\Models\HotelBooking;
use App\Services\HotelBooking\HotelBookingService;
use Illuminate\Http\Request;

class HotelBookingController extends Controller
{
    protected $service ;
    public function __construct(HotelBookingService $service){
        $this->service = $service ;
    }
    /**
     * Display all hotel bookings
     * @param \App\Http\Requests\HotelBooking\FilterHotelBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterHotelBookingRequest $request)
    {
        $this->authorize('viewAny',HotelBooking::class);
        $allBookings = $this->service->getAllHotelBookings($request->validated());
        return self::paginated($allBookings , HotelBookingResource::class);
    }

    /**
     * Store a new booking
     * @param \App\Http\Requests\HotelBooking\CreateHotelBookingRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateHotelBookingRequest $request)
    {
        $this->authorize('create',HotelBooking::class);
        $hotelBooking = $this->service->createBooking($request->validated());
        return self::success([new HotelBookingResource($hotelBooking)] , 201);
    }

    /**
     * Display a specific booking with its relationships
     * @param \App\Models\HotelBooking $hotelBooking
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(HotelBooking $hotelBooking)
    {
        $this->authorize('view',$hotelBooking);
        // $hotelBooking->load(['user' ,'getAmount' ,'room' ,'payments']);
        // return self::success([$hotelBooking->load(['user' ,'getAmount' ,'room' ,'payments'])]);
        return new HotelBookingResource($hotelBooking->load(['user' ,'room' ,'payments']));
    }

    /**
     * Update a booking
     * @param \App\Http\Requests\HotelBooking\UpdateHotelBookingRequest $request
     * @param \App\Models\HotelBooking $hotelBooking
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateHotelBookingRequest $request, HotelBooking $hotelBooking)
    {
        $this->authorize('update',$hotelBooking);
        $updateBooking = $this->service->updateBooking($request->validated() ,$hotelBooking);
        return self::success([new HotelBookingResource($updateBooking)]);
    }

    /**
     * Delete a booking
     * @param \App\Models\HotelBooking $hotelBooking
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(HotelBooking $hotelBooking)
    {
        $this->authorize('delete',$hotelBooking);
        $hotelBooking->delete();
        return self::success();
    }
    public function cancel(HotelBooking $hotelBooking){
       $info = $this->service->cancelBooking($hotelBooking);
       return $info['status'] == 'success' ? self::success([null] , $info['code'] ,$info['message']) :
       self::error('Error Occurred' ,$info['status'], $info['code'] ,[$info['message']]);

    }
}
