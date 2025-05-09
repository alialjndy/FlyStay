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
    protected function casts(): array{
        return [
            'check_in_date'=>'date',
            'check_out_date'=>'date',
            'booking_date'=>'date'
        ];
    }
}
