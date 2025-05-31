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
        'created_at',
        'updated_at'
    ];
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function scopeFilter($query,$filters){
        return $query
            ->when($filters['name'] ?? null , function($query,$name){
                $query->where('name','like',"%{$name}%");
            });
    }
    public function airports(){
        return $this->hasMany(Airport::class,'city_id');
    }
    public function hotels(){
        return $this->hasMany(Hotel::class,'city_id');
    }
}
