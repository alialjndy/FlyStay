<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    //
    protected $fillable = [
        'user_id'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function favoritable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
