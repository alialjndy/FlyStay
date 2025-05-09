<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $fillable = [
        'name',
        'iso2'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [

    ];
}
