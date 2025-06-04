<?php
namespace App\Services\Room;

use App\Models\Room;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class RoomService{
    protected $imageService ;
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService ;
    }
    /**
     * Retrieve a paginated list of all rooms with their associated hotel and images
     * @param mixed $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllRooms($request){
        return Room::filter($request)->with(['hotel','images'])->paginate(10);

    }
    /**
     * Create a new room and optionally store associated photos
     * @param mixed $data
     * @param mixed $photos
     * @return array{info: array, room: Room}
     */
    public function createRoom($data ,$photos = null){
        $room = Room::create($data);
        if(!empty($photos)){
            set_time_limit(60);
            $results = $this->imageService->storeMultipleImage($photos,$room);
        }
        return [
            'room'=>$room ,
            'info'=>$results ?? null
        ];
    }
    /**
     * Update room details, handle new image uploads and deletions
     * @param mixed $data
     * @param \App\Models\Room $room
     * @param mixed $newPhotos
     * @param mixed $imagesToDelete
     * @return array{message delete: array, message stored: array, updated: Room}
     */
    public function updateRoom($data , Room $room , $newPhotos = null , $imagesToDelete = []){
        $room->update($data);
        $messageDelete = null ;
        $messageCreated = null ;

        if(!empty($newPhotos)){
            $messageCreated = $this->imageService->storeMultipleImage($newPhotos,$room);
        }
        if(!empty($imagesToDelete)){
            $messageDelete = $this->imageService->deleteMultipleImages($imagesToDelete);
        }
        return [
            'updated'=>$room->refresh(),
            'message stored'=>$messageCreated ,
            'message delete'=>$messageDelete
        ];
    }
    /**
     * Delete a room and all its associated images
     * @param \App\Models\Room $room
     * @return array<array{id: mixed, message: string, path: mixed|array{message: string}>}
     */
    public function deleteRoom(Room $room){
        // Get all image IDs associated with the room
        $imagesToDelete = $room->images()->pluck('id')->toArray();

        $message = $this->imageService->deleteMultipleImages($imagesToDelete);
        $room->delete();
        return $message ;
    }
}
