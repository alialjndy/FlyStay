<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait ManageCache
{
    public function addToCache($tag , $key , $value , $ttl){
        $keys = Cache::get($tag , []);
        if(!in_array($key , $keys)){
            $keys [] = $key;
            Cache::put($key , $value , $ttl);
            Cache::put($tag , $keys , now()->addDays(10));
            return Cache::get($key);
        }else {
            return Cache::get($key);
        }
    }
    public function clearCache($tag){
        $keys = Cache::get($tag , []);
        foreach($keys as $key){
            Cache::forget($key);
        }
        Cache::forget($tag);
    }
    public function getFromCache($tag , $key){
        $keys = Cache::get($tag , []);
        if(in_array($key , $keys)){
            return Cache::get($key);
        }
        return null;

    }
}
