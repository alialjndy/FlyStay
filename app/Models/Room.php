<?php

namespace App\Models;

use Carbon\Carbon;
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
            ->when($filters['hotel_name'] ?? null ,function($query , $hotel_name){
                $query->whereHas('hotel',function($q) use ($hotel_name){
                    $q->where('name','like',"%{$hotel_name}%");
                });
            })
            ->when($filters['min_price'] ?? null ,function($query , $min_price){
                $query->where('price_per_night','>=',$min_price);
            })
            ->when($filters['max_price'] ?? null ,function($query , $max_price){
                $query->where('price_per_night','<=',$max_price);
            })
            ->when( ($filters['min_price'] ?? null) && ($filters['max_price'] ?? null) ,function($query , $min_price , $max_price){
                $query->whereBetween('price_per_night',$min_price  ,$max_price);
            })
            ->when($filters['capacity'] ?? null ,function($query , $capacity){
                $query->where('capacity','=',$capacity);
            });
    }
    public function hotelBookings(){
        return $this->hasMany(HotelBooking::class ,'room_id');
    }
    /**
     * Check if the room is currently available
     */
    public function isAvailable($startDate, $endDate){
        return !$this->hotelBookings()
            ->whereNotIn('status', ['cancelled', 'failed', 'complete'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in_date', [$startDate, $endDate])
                    ->orWhereBetween('check_out_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('check_in_date', '<=', $startDate)
                            ->where('check_out_date', '>=', $endDate);
                    });
            })
        ->exists();
        #TODO عندما يتم انتهاء موعد الحجز أي قام الشخص بالسكن بالغرفة وانتهى الحجز تصبح الحالة كومبليت
    }
    public function favorites(){
        return $this->morphMany(Favorite::class,'favoritable');
    }
}
