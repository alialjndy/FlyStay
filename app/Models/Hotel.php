<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    //
    protected $fillable = [
        'name',
        'city_id',
        'rating',
        'description',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
}
