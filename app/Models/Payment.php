<?php

namespace App\Models;

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

    ];
    protected function casts(){
        return [
            'amount'=>'decimal:2',
            'date'=>'date'
        ];
    }
    public function payable(){
        return $this->morphTo();
    }
}
