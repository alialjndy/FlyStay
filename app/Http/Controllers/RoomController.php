<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Http\Requests\Image\UpdateImageRequest;
use App\Http\Requests\Image\UploadImageRequest;
use App\Http\Requests\Room\CreateRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\Room;
use App\Services\Image\ImageService;
use App\Services\Room\RoomService;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use ManageCache ;
    protected $imageService;
    protected $roomService ;
    public function __construct(ImageService $imageService , RoomService $roomService){
        $this->imageService = $imageService ;
        $this->roomService = $roomService ;
    }
    /**
     * Retrieve a paginated list of rooms with optional filters.
     * The result is cached for 10 days.
     * @param \App\Http\Requests\Hotel\FilterHotelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterHotelRequest $request)
    {
        $cacheKey = 'all_rooms' . md5(json_encode($request->validated()));
        $allRooms = $this->getFromCache('rooms' , $cacheKey)
            ?? $this->addToCache(
                'rooms' ,
                $cacheKey ,
                $this->roomService->getAllRooms($request->validated()) ,
                now()->addDays(10)
            );
        return self::paginated($allRooms);
    }

    /**
     * Create a new room and upload its associated images.
     * Clears the cached room data after creation.
     * @param \App\Http\Requests\Room\CreateRoomRequest $request
     * @param \App\Http\Requests\Image\UploadImageRequest $uploadImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateRoomRequest $request , UploadImageRequest $uploadImageRequest)
    {
        $photos = $uploadImageRequest->file('images');
        $results = $this->roomService->createRoom($request->validated(),$photos);
        $this->clearCache('rooms');
        return self::success(['room'=>$results['room'],'photos'=>$results['info']],201);
    }

    /**
     * Retrieve a specific room along with its hotel and images.
     * The result is cached for 10 days.
     * @param \App\Models\Room $room
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $cacheKey = 'room_' . $room->id ;
        $room = $this->getFromCache('rooms' , $cacheKey)
            ?? $this->addToCache(
                'rooms' ,
                $cacheKey ,
                $room->load(['hotel','images']) ,
                now()->addDays(10)
            );
        return self::success([$room]);
    }

    /**
     * Update room information, upload new images, and delete selected ones.
     * Clears the cached room data after update.
     * @param \App\Http\Requests\Room\UpdateRoomRequest $request
     * @param \App\Models\Room $room
     * @param \App\Http\Requests\Image\UpdateImageRequest $updateImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoomRequest $request, Room $room , UpdateImageRequest $updateImageRequest)
    {
        $newPhotos = $updateImageRequest->file('new_photos'); // photos for append.

        $imagesToDelete = $updateImageRequest->input('deleted_photos'); // photos for removed

        $results = $this->roomService->updateRoom($request->validated(),$room,$newPhotos ,$imagesToDelete);

        $this->clearCache('rooms'); // Clear cached rooms since this room was updated
        return self::success([
            'room'=>$results['updated'],
            'message stored'=>$results['message stored'],
            'message delete'=>$results['message delete'],
        ]);
    }

    /**
     * Delete a room and all its associated images.
     * Clears the cached room data after deletion.
     * @param \App\Models\Room $room
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Room $room)
    {
        $message = $this->roomService->deleteRoom($room);
        $this->clearCache('rooms');
        return self::success([$message]);
    }
}
