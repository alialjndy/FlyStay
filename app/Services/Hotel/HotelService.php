<?php
namespace App\Services\Hotel;

use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Models\Hotel;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class HotelService{
    protected $imageService ;
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService ;
    }
    /**
     * Retrieve all hotels with filtering, pagination, and eager-loaded relations
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllHotels(array $filters){
        return Hotel::filter($filters)->with(['country','city','images','rooms'])->paginate(10);
    }
    /**
     * Create a new hotel and optionally store associated images
     * @param array $data
     * @param mixed $photos
     * @return array{hotel: Hotel, info: array}
     */
    public function createHotel(array $data , $photos = null){
        set_time_limit(60);
        $hotel = Hotel::create($data);
        if($photos){
            $info = $this->imageService->storeMultipleImage($photos,$hotel);
        }
        return [
            'hotel'=>$hotel,
            'info'=>$info ?? null
        ] ;
    }
    /**
     * Update a hotel, upload new images, and delete selected ones
     * @param array $data
     * @param \App\Models\Hotel $hotel
     * @param mixed $newPhotos
     * @param mixed $imagesToDelete
     * @return array{message deleted: array, messageStored: array, updated: Hotel}
     */
    public function updateHotel(array $data , Hotel $hotel , $newPhotos = null , $imagesToDelete = []){
        $hotel->update($data);
        $messageStored = null;

        if(!empty($imagesToDelete)){
            $deletedMessages = $this->imageService->deleteMultipleImages($imagesToDelete);
        }
        if(!empty($newPhotos)){
            $messageStored = $this->imageService->storeMultipleImage($newPhotos , $hotel);
        }
        return [
            'updated'=>$hotel->refresh(),
            'message deleted'=>$deletedMessages ?? [],
            'messageStored'=>$messageStored ?? []
        ];
    }
    /**
     * Delete a hotel and all its associated images
     * @param \App\Models\Hotel $hotel
     * @return array<array{id: mixed, message: string, path: mixed|array{message: string}>}
     */
    public function deleteHotel(Hotel $hotel){
        $imagesId = $hotel->images()->pluck('id')->toArray();
        $messages = $this->imageService->deleteMultipleImages($imagesId);
        $hotel->delete();
        return $messages;
    }

}
