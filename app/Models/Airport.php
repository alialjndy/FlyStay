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
        'created_at',
        'updated_at'
    ];
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function country(){
        return $this->hasOneThrough(
            Country::class,
            City::class,
            'id',
            'id',
            'city_id',
            'country_id',
        );
    }
}
