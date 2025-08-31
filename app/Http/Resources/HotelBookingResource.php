<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelBookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'room_id'=>$this->room_id,
            'check_in_date'=>Carbon::parse($this->check_in_date)->toFormattedDateString(),
            'check_out_date'=>Carbon::parse($this->check_out_date)->toFormattedDateString(),
            'booking_date'=>Carbon::parse($this->booking_date)->toFormattedDateString(),
            'amount'=> $this->getAmount(),
            'status'=>$this->status,
            'duration'=>$this->check_in_date->diffInDays($this->check_out_date).' Days',
            'user_Info' => $this->user,
            'Room' => [
                'id' => $this->room->id,
                'hotel_id' => $this->room->hotel_id,
                'room_type' => $this->room->room_type,
                'price_per_night' => $this->room->price_per_night,
                'capacity' => $this->room->capacity,
                'description' => $this->room->description,
            ],
            'Hotel'=>new HotelResource($this->room->hotel),
            'Payments' => $this->payments,
        ];
    }
}
