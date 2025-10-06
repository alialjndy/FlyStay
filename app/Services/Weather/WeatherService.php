<?php
namespace App\Services\Weather;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class WeatherService{
    public static function getWeatherInfo(array $data){
        $city = $data['city'] ;
        $targetDate  = $data['targetDate'];
        $apiKey = config('services.weather.key');

        $diffDays = now()->diffInDays(Carbon::parse($targetDate));
        if ($diffDays > 14) {
            return [
                'status'  => 'failed',
                'message' => 'Forecasts are available only for up to 14 days in advance.'
            ];
        }
        $client = new Client([
            'base_uri'=>'http://api.weatherapi.com/v1/' ,
            'timeout'=> 25.0 , // max wait time ,
            'verify' => false
        ]);

        try{
            $response = $client->get('forecast.json',[
                'query'=>[
                    'key'   =>$apiKey,
                    'q'     =>$city ,
                    'days'  => 14
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            $targetDate = Carbon::parse($targetDate)->format('Y-m-d');

            $forecastDays = $data['forecast']['forecastday'];
            $dayData = collect($forecastDays)->firstWhere('date', $targetDate);

            if ($dayData) {
                return [
                    'status'=>'success',
                    'weather_data' => [
                        'location'    => $data['location']['name'],
                        'date'        => $dayData['date'],
                        'maxtemp'     => $dayData['day']['maxtemp_c'] . "°C",
                        'mintemp'     => $dayData['day']['mintemp_c'] . "°C",
                        'condition'   => $dayData['day']['condition']['text'],
                        'icon'        => $dayData['day']['condition']['icon'],
                    ]
                ];
            } else {
                return [
                    'status'=>'failed',
                    'message'=>'No forecast available for this date. Try closer to the trip date.'
                ];
            }
        }catch(RequestException $e){
            Log::info('error in fetch weather data '. $e->getMessage());
            throw new Exception('Unable to fetch weather data. Please try again later.',500);
        }
    }
}
