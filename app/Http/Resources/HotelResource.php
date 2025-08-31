<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "city_name"=>$this->city->name,
            "country_name"=>$this->city->country->name ,
            "rating"=>$this->rating,
            "description"=>$this->description
        ];
    }
}
