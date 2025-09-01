<?php
namespace App\Services\Rating;

use App\Models\Hotel;
use App\Models\Rating;
use Tymon\JWTAuth\Facades\JWTAuth;

class RatingService{
    public function getAllRating(){
        return Rating::with(['user','hotel'])->paginate(10);
    }
    /**
     * Create a new rating or update an existing one if the user already rated the hotel
     * @param array $data
     * @return array{code: int, data: Rating, status: string}
     */
    public function createOrUpdateRating(array $data){
        $authUser = JWTAuth::parseToken()->authenticate();

        // Check if the user has already rated this hotel
        $rating = Rating::where('user_id',$authUser->id)
            ->where('hotel_id',$data['hotel_id'])->first();

        // If a rating exists -> update it
        if($rating){
            $rating->update([
                'rating'=>$data['rating'] ,
                'description'=>$data['description'] ?? null
            ]);
            return [
                'status'=>'success',
                'data'=>$rating->refresh() ,
                'code'=>200
            ];
        }

        // If no rating exists → create a new one
        $createdRating = Rating::create([
            'user_id'     => $authUser->id ,
            'hotel_id'    => $data['hotel_id'] ,
            'rating'      => $data['rating'] ,
            'description' => $data['description'] ?? null
        ]);
        return [
            'status'=>'success',
            'data'=>$createdRating ,
            'code' => 201
        ];
    }
    /**
     * Update an existing rating and return the fresh instance
     * @param array $data
     * @param \App\Models\Rating $rating
     * @return Rating
     */
    public function updateRating(array $data , Rating $rating){
        $rating->update($data);
        return $rating->refresh();
    }
}
