<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = [
        'name',
        'country_id'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
}
