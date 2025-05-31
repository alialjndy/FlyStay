<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Models\Hotel;
use App\Services\Hotel\HotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $hotelService ;
    public function __construct(HotelService $hotelService){
        $this->hotelService = $hotelService ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(FilterHotelRequest $request)
    {
        $hotels = $this->hotelService->getAllHotels($request->validated());
        return self::paginated($hotels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateHotelRequest $request)
    {
        $hotel = $this->hotelService->createHotel($request->validated());
        return self::success([$hotel],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        $hotel = $hotel->load(['country','city']);
        return self::success([$hotel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $updated = $this->hotelService->updateHotel($request->validated(),$hotel);
        return self::success([$updated]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $this->hotelService->deleteHotel($hotel);
        return self::success([null]);
    }
}
