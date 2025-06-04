<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\UploadImageRequest;
use App\Models\Image;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $imageService;
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService ;
    }
    // public function upload(UploadImageRequest $request){
    //     $result = $this->imageService->storeMultipleImage($request->validated());
    //     return self::success($result);
    // }
}
