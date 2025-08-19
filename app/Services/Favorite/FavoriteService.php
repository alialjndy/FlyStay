<?php
namespace App\Services\Favorite;

use App\Models\Hotel;
use App\Models\Room;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class FavoriteService{
    /**
     * Toggle favorite for a given model (Hotel/Room) by ID.
     * If the record is already favorited by the user, it will be removed.
     * Otherwise, it will be added.
     * @param string $model
     * @param mixed $id
     * @return array{code: mixed, message: mixed, status: mixed}
     */
    public function toogleFavorite(string $model , $id){
        try{
            $user = JWTAuth::parseToken()->authenticate();

            // Get the model class (Hotel/Room) dynamically
            $modelClass = $this->getClass($model);

            // Find the model instance or fail (throws exception if not found)
            $modelInstance = $modelClass::findOrFail($id);

            // Check if the current user already marked this item as favorite
            $existingFavorite = $modelInstance->favorites()->where('user_id' , $user->id)->first();

            if($existingFavorite){
                // If favorite exists, remove it
                $existingFavorite->delete();
                return $this->getMessage('success',ucfirst($model) .' removed from favorite', 200);
            }else{
                $modelInstance->favorites()->create([
                    'user_id'=>$user->id
                ]);
                return $this->getMessage('success',ucfirst($model) .' added to favorite', 200);
            }
        }catch(Exception){
            return $this->getMessage('error','Operation failed',500);
        }
    }
    private function getClass(string $type){
        $models = [
            'hotel' => Hotel::class,
            'room' => Room::class,
        ];
        if(!array_key_exists($type ,$models)){
            throw new \InvalidArgumentException('Model type not supported');
        }
        return $models[$type];
    }
    private function getMessage($status , $message ,$code = 400) {
        return [
            'status'=> $status ,
            'message' => $message,
            'code'=> $code
        ];
    }
}
