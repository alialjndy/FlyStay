<?php

namespace App\Http\Controllers;

use App\Http\Requests\City\FilterCityRequest;
use App\Models\City;
use Illuminate\Http\Request;
use App\Traits\ManageCache ;
use Illuminate\Support\Facades\Cache;

class CityController extends Controller
{
    use ManageCache;
    /**
     * Display a listing of the resource.
     */
    public function index(FilterCityRequest $request)
    {
        $cityName = $request->validated()['name'] ?? 'all';
        $cacheKey = 'all_cities_' . $cityName ;

        $cities = $this->getFromCache('cities' , $cacheKey) ??
            $this->addToCache(
                'cities',
                $cacheKey ,
                City::filter($request->validated())->with(['country','airports','hotels'])->paginate(10) ,
                now()->addDays(15) );

        return self::paginated($cities);
    }
    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $cacheKey = 'city_' . $city->id ;
        $city = $this->getFromCache('cities' , $cacheKey) ??
            $this->addToCache(
                'cities',
                $cacheKey ,
                $city->load(['country','airports','hotels']) ,
                now()->addDays(15));

        return self::success([$city]);
    }
}
