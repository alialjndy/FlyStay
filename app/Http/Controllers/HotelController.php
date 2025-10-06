<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Http\Requests\Image\UploadImageRequest;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use App\Services\Hotel\HotelService;
use App\Services\Image\ImageService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use ManageCache ;
    protected $hotelService ;
    protected $imageService ;
    public function __construct(HotelService $hotelService , ImageService $imageService){
        $this->hotelService = $hotelService ;
        $this->imageService = $imageService ;
    }
    /**
     * Display a paginated list of hotels with optional filters.
     * The result is cached for 20 minutes.
     * @param \App\Http\Requests\Hotel\FilterHotelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterHotelRequest $request)
    {
        $cacheKey = 'all_hotels_' . md5(json_encode($request->validated() ?? 'all'));

        // Retrive data of hotels from cache if it exist , otherwise load data and store it in cache.
        $hotels = $this->getFromCache('hotels' , $cacheKey)
            ?? $this->addToCache(
                'hotels' ,
                $cacheKey ,
                $this->hotelService->getAllHotels($request->validated()) ,
                1200);
        return self::paginated($hotels);
    }

    /**
     * Store a newly created hotel and upload associated images
     * Clears any cached hotel data after creation.
     * @param \App\Http\Requests\Hotel\CreateHotelRequest $request
     * @param \App\Http\Requests\Image\UploadImageRequest $uploadImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateHotelRequest $request , UploadImageRequest $uploadImageRequest)
    {
        $photos = $uploadImageRequest->file('images');
        $data = $this->hotelService->createHotel($request->validated() , $photos);
        $this->clearCache('hotels');
        return self::success([$data['hotel'],$data['info']],201);
    }

    /**
     * Show detailed information for a single hotel, including relations
     * The result is cached for 20 minutes.
     * @param \App\Models\Hotel $hotel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Hotel $hotel)
    {
        $cacheKey = 'hotel_' . $hotel->id ;

        // Retrive data of hotels from cache if it exist , otherwise load data and store it in cache.
        $hotel = $this->getFromCache('hotels' , $cacheKey)
            ?? $this->addToCache(
                'hotels' ,
                $cacheKey ,
                $hotel->load(['country','city','images','rooms']) ,
                1200);
        return self::success([$hotel]);
    }

    /**
     * Update a hotel's data and handle image updates (add/remove)
     * Clears any cached hotel data after creation.
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
        $this->clearCache('hotels');
        return self::success([$data]);
    }

    /**
     * Delete a hotel and its associated images
     * Clears any cached hotel data after creation.
     * @param \App\Models\Hotel $hotel
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Hotel $hotel)
    {
        $this->authorize('delete',$hotel);
        $messages = $this->hotelService->deleteHotel($hotel);
        $this->clearCache('hotels');
        return self::success([$messages]);
    }
    public function suggestHotels($destinationCityId){
        $suggestHotel = Hotel::where('city_id',$destinationCityId)->paginate(10);
        return self::paginated($suggestHotel , HotelResource::class);
    }
}
