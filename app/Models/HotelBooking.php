<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory ;
    //
    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'booking_date',
        'status',
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'check_in_date'=>'datetime',
        'check_out_date'=>'datetime',
        'booking_date'=>'date'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function payments(){
        return $this->morphMany(Payment::class,'payable');
    }
    public function room(){
        return $this->belongsTo(Room::class,'room_id');
    }
    public function getAmount(){
        $days = $this->check_in_date->diffInDays($this->check_out_date);
        return $this->room->price_per_night * max($days ,1);
    }
    public function scopeFilter($query ,array $filters = []){
        return $query
            ->when($filters['status'] ?? null,function($query) use($filters){
                $query->where('status','=', $filters['status']);
            })
            ->when($filters['booking_date'] ?? null ,function($query) use ($filters){
                $query->where('booking_date' ,'=' ,$filters['booking_date']);
            })
            ->when(($filters['from_date']) ?? null && ($filters['to_date'] ?? null) || ($filters['sort_type'] ?? null), function($query) use ($filters){
                $query->whereBetween('booking_date',[
                    Carbon::parse($filters['from_date'])->startOfDay(),
                    Carbon::parse($filters['to_date'])->endOfDay()
                ]);
            })->orderBy('booking_date',$filters['sort_type'] ?? 'desc');
    }
}
