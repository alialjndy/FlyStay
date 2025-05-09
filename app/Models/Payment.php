<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'amount',
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
}
