<?php

namespace App\Traits;

use App\Services\Weather\WeatherService;
use Illuminate\Support\Facades\Log;

trait Weather
{
    public function get_weather($city , $targetDate){

        $weather_data = WeatherService::getWeatherInfo($city,$targetDate);
        if($weather_data['status'] === 'success'){
            Log::info(
                $weather_data['weather_data']['location'] . 'Next info is ' .$weather_data['weather_data']['date'] . 'next info is ' . $weather_data['weather_data']['maxtemp']
            );
            return $weather_data ;
        }
    }
}
