<?php
namespace App\Services\Hotel;

use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelService{
    public function getAllHotels(array $filters){
        return Hotel::filter($filters)->with(['country','city'])->paginate(10);
    }
    public function createHotel(array $data){
        return Hotel::create($data);
    }
    public function updateHotel(array $data , Hotel $hotel){
        $hotel->update($data);
        return $hotel->refresh();
    }
    public function deleteHotel(Hotel $hotel){
        $hotel->delete();
    }

}
