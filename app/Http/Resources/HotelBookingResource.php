<?php

namespace App\Http\Resources;

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
            'check_in_date'=>$this->check_in_date,
            'check_out_date'=>$this->check_out_date,
            'booking_date'=>$this->booking_date,
            'amount'=> $this->getAmount(),
            'status'=>$this->status,
            'duration'=>$this->check_in_date->diffInDays($this->check_out_date).' Days',
            'user_Info' => $this->user,
            'Room' => $this->room,
            'Payments' => $this->payments,
        ];
    }
}
