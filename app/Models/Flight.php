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
            ->when($filters['arrival_country'] ?? null, function($query, $arrival_country) {
                $query->whereRelation('arrivalAirport.country','countries.name','LIKE',"%{$arrival_country}%");
            })
            ->when($filters['departure_country'] ?? null, function($query , $departure_country){
                $query->whereRelation('departureAirport.country','countries.name','LIKE',"%{$departure_country}%");
            })
            ->when($filters['arrival_city'] ?? null ,function($query , $arrival_city){
                $query->whereRelation('arrivalAirport.city','cities.name','LIKE',"%{$arrival_city}%");
            })
            ->when($filters['departure_city'] ?? null , function($query , $departure_city){
                $query->whereRelation('departureAirport.city','cities.name','LIKE',"%{$departure_city}%");
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
    public function  favorites(){
        return $this->morphMany(Favorite::class, 'favoritable');
    }
    public function arrivalCountry(){
        return $this->arrivalAirport()->country() ;
    }
    public function departureCountry(){
        return $this->arrivalAirport()->country();
    }
}
