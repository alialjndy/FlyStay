<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = [
        'image_path'
    ];
    protected $guarded = [
        //
    ];
    protected $hidden = [
        'imageable_id',
        'created_at',
        'updated_at',

    ];
    public function imageable(){
        return $this->morphTo();
    }
}
