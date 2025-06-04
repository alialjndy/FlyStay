<?php
namespace App\Services\Image;

use App\Models\Image;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageService{
    protected array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    public function storeMultipleImage(array $files, Model $model){
        $results = [];
        foreach($files as $file){
            $this->validateFileName($file);
            $this->validateMimeType($file);

            if(!$this->isSafeImage($file)){
                $results[] = [
                    'status' => 'failed',
                    'message' => 'The image was rejected because it contains malicious content.',
                    'original_name'=>$file->getClientOriginalName(),
                ];
                continue;
            }

            $fileName = Str::random(32) . '.' . $file->getClientOriginalExtension();
            $filePath = 'Images/' . $fileName;

            Storage::disk('local')->putFileAs('Images', $file, $fileName);
            $url = Storage::disk('local')->url($filePath);

            $model->images()->create([
                'image_path'=>$filePath
            ]);

            $results[] = [
                'status' => 'success',
                'message' => 'Image stored successfully.',
                'path' => $filePath,
                'url' => $url,
                'original_name' => $file->getClientOriginalName(),
            ];
        }
        return $results ;
    }
    private function isSafeImage($file){
        $response = Http::timeout(60)->withHeaders([
            'x-apikey'=>env('VIRUSTOTAL_API_KEY'),
        ])->attach(
            'file',file_get_contents($file) ,$file->getClientOriginalName(),
        )->post('https://www.virustotal.com/api/v3/files');

        $result = $response->json();

        $analysisId = $result['data']['id'] ?? null;
        if(!$analysisId){return false ;}

        sleep(8);

        $analysisResponse = Http::withHeaders([
            'x-apikey' => env('VIRUSTOTAL_API_KEY'),
        ])->get("https://www.virustotal.com/api/v3/analyses/{$analysisId}");

        $analysisData = $analysisResponse->json();
        $stats = $analysisData['data']['attributes']['stats'];
        return ($stats['malicious'] ?? 1) == 0 && ($stats['suspicious'] ?? 1) == 0;
    }

    private function validateFileName($file){
        $name = $file->getClientOriginalName();

        if (preg_match('/\.[^.]+\./', $name)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }

        if (str_contains($name, '..') || str_contains($name, '/') || str_contains($name, '\\')) {
            throw new Exception(trans('general.pathTraversalDetected'), 403);
        }
    }
    private function validateMimeType($file): void
    {
        $mime = $file->getClientMimeType();
        if (!in_array($mime, $this->allowedMimeTypes)) {
            throw new FileException(trans('general.invalidFileType'), 403);
        }
    }
    public function deleteMultipleImages(array $imagesId){
        $results = [] ;
        if(!empty($imagesId)){
            foreach($imagesId as $id){
                $image = Image::findOrFail($id);
                $file_path = $image->image_path ;

                $image->delete();
                Storage::disk('local')->delete($file_path);
                $results[] = [
                    'id' => $id,
                    'message' => 'Image deleted successfully',
                    'path' => $file_path,
                ];
            }
        }else{
            $results[] = [
                'message'=>'no photos found'
            ];
        }
        return $results ;
    }
}
