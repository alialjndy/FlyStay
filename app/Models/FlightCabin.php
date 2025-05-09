<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightCabin extends Model
{
    //
    protected $fillable = [
        'flight_id',
        'class_name',
        'price',
        'available_seats'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
    protected function casts(){
        return [
            'price'=>'decimal:2',
            'available_seats'=>'integer'
        ];
    }
}
