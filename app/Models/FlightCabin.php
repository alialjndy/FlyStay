<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightCabin extends Model
{
    use HasFactory;
    protected $fillable = [
        'flight_id',
        'class_name',
        'price',
        'available_seats',
        'note'
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
            'price'=>'decimal:2',
            'available_seats'=>'integer'
        ];
    }
    public function flight(){
        return $this->belongsTo(Flight::class, 'flight_id');
    }
    public function bookings(){
        return $this->hasMany(FlightBooking::class, 'flight_cabins_id');
    }
    // public function getAvailableSeatsAttribute(){
    //     $bookedSeats = $this->bookings()->pluck('seat_number')->toArray();
    //     $allSeats = range($this->start_seat,$this->end_seat);
    //     return array_diff($allSeats , $bookedSeats);
    // }
}
