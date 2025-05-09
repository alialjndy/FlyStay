<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    protected $fillable = [
        'user_id',
        'flight_cabins_id',
        'booking_date',
        'seat_number',
        'status',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
    protected function casts(): array{
        return [
            'booking_date'=>'date',
            'seat_number'=>'integer'
        ];
    }
}
