<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'transaction_id',
        'verified_by',
        'status',
        'date',
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
            'amount'=>'decimal:2',
            'date'=>'datetime'
        ];
    }
    public function payable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->toFormattedDateString();
    }
    public function scopeFilter($query , $filters){
        return $query
            ->when($filters['status'] ?? null ,function($query , $status){
                $query->where('status',$status);
            })
            ->when($filters['method'] ?? null ,function($query , $method){
                $query->where('method',$method);
            })
            ->when($filters['amount'] ?? null ,function($query , $amount){
                $query->where('amount','>=' , $amount);
            })
            ->when($filters['date'] ?? null ,function($query , $date){
                $query->where('date','=',$date);
            })
            ->when(($filters['from_date'] ?? null) && ($filters['to_date'] ?? null) || ($filters['sort_type'] ?? null), function($query) use($filters){
                $query->whereBetween('date',[
                    Carbon::parse($filters['from_date'])->startOfDay(),
                    Carbon::parse($filters['to_date'])->endOfDay()
                ])->orderBy('date',$filters['sort_type'] ?? 'desc');
            })
            ->when($filters['object_type'] ?? null , function($query , $filters){
                $query->where('payable_type','LIKE',$filters['object_type']);
            });

        ;
    }
}
