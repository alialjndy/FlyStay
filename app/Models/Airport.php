<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    //
    protected $fillable = [
        'name',
        'city_id',
        'IATA_code'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
}
