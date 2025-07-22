<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    //
    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'booking_date',
        'status',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
    protected $casts = [
        'check_in_date'=>'datetime',
        'check_out_date'=>'datetime',
        'booking_date'=>'date'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function payments(){
        return $this->morphMany(Payment::class,'payable');
    }
    public function room(){
        return $this->belongsTo(Room::class,'room_id');
    }
    public function getAmount(): int{
        $days = $this->check_out_date->diffInDays($this->check_in_date);
        return $this->room->price_per_night * max($days ,1);
    }
}
