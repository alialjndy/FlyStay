<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    //
    protected $fillable = [
        'airline',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
}
