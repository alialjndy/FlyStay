<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory ;
    protected $fillable = [
        'hotel_id',
        'room_type',
        'price_per_night',
        'capacity',
        'description',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected function casts(){
        return [
            'price_per_night'=>'decimal:2'
        ];
    }
    public function images(){
        return $this->morphMany(Image::class,'imageable');
    }
    public function hotel(){
        return $this->belongsTo(Hotel::class , 'hotel_id');
    }
    public function scopeFilter($query ,$filters){
        return $query
            ->when($filters['hotel_name'],function($query , $hotel_name){
                $query->whereHas('hotel',function($q) use ($hotel_name){
                    $q->where('name','like',"%{$hotel_name}%");
                });
            })
            ->when($filters['min_price'],function($query , $min_price){
                $query->where('price_per_night','>=',$min_price);
            })
            ->when($filters['max_price'],function($query , $max_price){
                $query->where('price_per_night','<=',$max_price);
            })
            ->when($filters['min_price'] && $filters['max_price'],function($query , $min_price , $max_price){
                $query->whereBetween('price_per_night',$min_price  ,$max_price);
            })
            ->when($filters['capacity'],function($query , $capacity){
                $query->where('capacity','=',$capacity);
            });
    }
}
