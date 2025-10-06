<?php
namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;

class CacheService{
    // Cache service implementation
    public function addToCache(string $key, $value, $ttl){
        $value = Cache::flexible($key, [$ttl , $ttl + 120] , fn() => $value);
        return $value;
    }
    public function getFromCache(string $key){
        return Cache::memo()->get($key , $default = null);
    }
    public function clearCache(string $key){
        Cache::forget($key);
    }

}
