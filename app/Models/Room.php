<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_type',
        'price_per_night',
        'capacity',
        'description',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
    protected function casts(){
        return [
            'price_per_night'=>'decimal:2'
        ];
    }
}
