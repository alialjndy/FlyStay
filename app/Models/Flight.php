<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory ;
    //
    protected $fillable = [
        'airline',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function scopeFilter($query , $filters){
        return $query
            ->when($filters['old_flights'] ?? null,function($query){
                $query->where('departure_time','<=',Carbon::now());
            })
            ->when($filters['later_flight'] ?? null , function($query){
                $query->where('departure_time','>=', Carbon::now());
            })
            ->when($filters['airline'] ?? null , function($query , $airline){
                $query->where('airline','LIKE',$airline);
            })
            ->when(($filters['from_date'] ?? null) && ($filters['to_date']?? null),function($query) use($filters){
                $query->whereBetween('departure_time',[$filters['from_date'],$filters['to_date']]);
            })
            ->when($filters['arrival_country'] ?? null , function($query) use($filters){
                $Arrivalcountry = $this->arrivalAirport->country;
                $query->where($filters['arrival_country'],'LIKE',$Arrivalcountry);
            })
            ;
    }
    public function departureAirport(){
        return $this->belongsTo(Airport::class,'departure_airport_id');
    }
    public function arrivalAirport(){
        return $this->belongsTo(Airport::class,'arrival_airport_id');
    }
    public function flightDetails(){
        return $this->hasMany(FlightCabin::class,'flight_id');
    }
}
