<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

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
        'created_at',
        'updated_at'
    ];
    public function cities(){
        return $this->hasMany(City::class,'country_id');
    }
    public function scopeFilter($query , $filters){
        return $query
            ->when($filters['name'] ?? null , function($query,$name){
                $query->where('name','like',"%{$name}%");
            })
            ->when($filters['iso2'] ?? null, function($query , $iso2){
                $query->where('iso2','like',"%{$iso2}%");
            });
    }
}
