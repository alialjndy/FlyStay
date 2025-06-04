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
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $imageService;
    protected $roomService ;
    public function __construct(ImageService $imageService , RoomService $roomService){
        $this->imageService = $imageService ;
        $this->roomService = $roomService ;
    }
    /**
     * Display a paginated list of rooms with optional filters.
     * @param \App\Http\Requests\Hotel\FilterHotelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilterHotelRequest $request)
    {
        $allRooms = $this->roomService->getAllRooms($request);
        return self::paginated($allRooms);
    }

    /**
     * Store a new room and associated images.
     * @param \App\Http\Requests\Room\CreateRoomRequest $request
     * @param \App\Http\Requests\Image\UploadImageRequest $uploadImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(CreateRoomRequest $request , UploadImageRequest $uploadImageRequest)
    {
        $photos = $uploadImageRequest->file('images');
        $results = $this->roomService->createRoom($request->validated(),$photos);
        return self::success(['room'=>$results['room'],'photos'=>$results['info']]);
    }

    /**
     * Display a specific room along with its hotel and images.
     * @param \App\Models\Room $room
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $room = $room->load(['hotel','images']);
        return self::success([$room]);
    }

    /**
     * Update room information, upload new images, and delete selected ones.
     * @param \App\Http\Requests\Room\UpdateRoomRequest $request
     * @param \App\Models\Room $room
     * @param \App\Http\Requests\Image\UpdateImageRequest $updateImageRequest
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRoomRequest $request, Room $room , UpdateImageRequest $updateImageRequest)
    {
        $newPhotos = $updateImageRequest->file('new_photos');
        $imagesToDelete = $updateImageRequest->input('deleted_photos');
        $results = $this->roomService->updateRoom($request->validated(),$room,$newPhotos ,$imagesToDelete);
        return self::success([
            'room'=>$results['updated'],
            'message stored'=>$results['message stored'],
            'message delete'=>$results['message delete'],
        ]);
    }

    /**
     * Delete a room and all its associated images.
     * @param \App\Models\Room $room
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Room $room)
    {
        $message = $this->roomService->deleteRoom($room);
        return self::success([$message]);
    }
}
