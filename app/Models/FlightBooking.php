<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'flight_cabins_id',
        'booking_date',
        'seat_number',
        'status',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected function casts(): array{
        return [
            'booking_date'=>'datetime',
            'seat_number'=>'integer'
        ];
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function getFormattedBookingDateAttribute(){
        return $this->booking_date->format('Y-m-d H:i:s');
    }
    public function flightCabin(){
        return $this->belongsTo(FlightCabin::class,'flight_cabins_id');
    }
    public function payments(){
        return $this->morphMany(Payment::class,'payable');
    }
    public function scopeFilter($query , array $filters = []){
        return $query
            ->when($filters['status'] ?? null , function($query , $status) {
                $query->where('status',$status);
            })
            ->when(($filters['from_date'] ?? null ) && ($filters['to_date'] ?? null) , function($query) use ($filters){
                $query->whereBetween('booking_date',[
                    Carbon::parse($filters['from_date'])->startOfDay() ,
                    Carbon::parse($filters['to_date'])->endOfDay()
                ]);
            })
            ->when($filters['flight_cabin'] ?? null, function($query) use($filters) {
                $query->whereRelation('flightCabin','class_name','like',$filters['flight_cabin']);
            });
    }
    public function getAmount(){
        return $this->flightCabin->price ;
    }
    public function DestinationCity(){
        return $this->flightCabin->flight->arrivalAirport->city ;
    }
    public function SuggestHotels(){
        $recommindationHotels = Hotel::where('city_id',$this->DestinationCity()->id);
        return $recommindationHotels ;
    }
}
