<?php

namespace App\Http\Controllers;

use App\Http\Requests\Weather\WeatherRequest;
use App\Services\Weather\WeatherService;

class WeatherController extends Controller
{
    protected $service ;
    public function __construct(WeatherService $service){
        $this->service = $service ;
    }
    public function getWeatherInfo(WeatherRequest $request){
        $data = $this->service->getWeatherInfo($request->validated());
        return $data['status'] === 'success' ?
            self::success([$data]) :
            self::error('Error Occurred', 'error' , 500 ,[$data['message']]);
    }
}
