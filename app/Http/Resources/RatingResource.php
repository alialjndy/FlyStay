<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         =>$this->id ,
            'user_id'    =>$this->user_id ,
            'rating'     =>$this->rating ,
            'description'=>$this->description ,
            'user' => [
                'id'     =>$this->user->id ,
                'name'   =>$this->user->name ,
                'email'  =>$this->user->email ,
            ],
            'hotel'=> [
                'id'            =>$this->hotel->id ,
                'name'          =>$this->hotel->name ,
                'city_name'     =>$this->hotel->city->name ,
                'country_name'  =>$this->hotel->country->name ,
                'rating'        =>$this->rating ,
                'description'   =>$this->description
            ],
        ];
    }
}
