<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Http\Requests\Image\UploadImageRequest;
use App\Models\Hotel;
use App\Services\Hotel\HotelService;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected $hotelService ;
    protected $imageService ;
    public function __construct(HotelService $hotelService , ImageService $imageService){
        $this->hotelService = $hotelService ;
        $this->imageService = $imageService ;
    }
    /**
     * Display a paginated list of hotels with optional filters
     * @param \App\Http\Requests\Hotel\FilterHotelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterHotelRequest $request)
    {
        $hotels = $this->hotelService->getAllHotels($request->validated());
        return self::paginated($hotels);
    }

    /**
     * Store a newly created hotel and upload associated images
     * @param \App\Http\Requests\Hotel\CreateHotelRequest $request
     * @param \App\Http\Requests\Image\UploadImageRequest $uploadImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateHotelRequest $request , UploadImageRequest $uploadImageRequest)
    {
        $photos = $uploadImageRequest->file('images');
        $data = $this->hotelService->createHotel($request->validated() , $photos);
        return self::success([$data['hotel'],$data['info']],201);
    }

    /**
     * Show detailed information for a single hotel, including relations
     * @param \App\Models\Hotel $hotel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Hotel $hotel)
    {
        $hotel = $hotel->load(['country','city','images','rooms']);
        return self::success([$hotel]);
    }

    /**
     * Update a hotel's data and handle image updates (add/remove)
     * @param \App\Http\Requests\Hotel\UpdateHotelRequest $request
     * @param \App\Http\Requests\Image\UpdateImageRequest $updateImageRequest
     * @param \App\Models\Hotel $hotel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateHotelRequest $request,UpdateImageRequest $updateImageRequest,Hotel $hotel)
    {
        $newPhotos = $updateImageRequest->file('new_photos');
        $imagesToDelete = $updateImageRequest->input('deleted_photos');

        $data = $this->hotelService->updateHotel($request->validated(),$hotel ,$newPhotos ,$imagesToDelete);
        return self::success([$data]);
    }

    /**
     * Delete a hotel and its associated images
     * @param \App\Models\Hotel $hotel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Hotel $hotel)
    {
        $this->authorize('delete',$hotel);
        $messages = $this->hotelService->deleteHotel($hotel);
        return self::success([$messages]);
    }
}
