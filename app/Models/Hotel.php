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
            'country_id'
        );
    }
    public function scopeFilter($query , $filters){
        return $query
            ->when($filters['name'] ?? null , function($query,$name){
                $query->where('name','like',"%{$name}%");
            })
            ->when($filters['rating'] ?? null , function($query , $rating){
                $query->where('rating','=',$rating);
            })
            ->when($filters['city'] ?? null , function($query , $city){
                $query->whereHas('city',function($q) use ($city){
                    $q->where('name','like',"%{$city}%");
                });

            })
            ->when($filters['country'] ?? null , function($query , $country){
                $query->whereHas('city.country',function($q) use ($country){
                    $q->where('name','like',"%{$country}%");
                });
            });
    }
}
