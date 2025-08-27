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
    protected $appends = ['url'];
    public function imageable(){
        return $this->morphTo();
    }
    public function getUrlAttribute(){
        return url('storage/'. $this->image_path);
    }

}
