<?php

namespace App\Http\Controllers;

use App\Http\Requests\City\FilterCityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterCityRequest $request)
    {
        $cities = City::filter($request->validated())->with(['country','airports','hotels'])->paginate(10);
        return self::paginated($cities);
    }
    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city = $city->load(['country','airports','hotels']);
        return self::success([$city]);
    }
}
