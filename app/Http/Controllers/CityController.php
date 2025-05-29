<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #TODO إنشاء فورم ريكويست
    public function index(Request $request)
    {
        $cities = City::filter($request)->with('country')->paginate(10);
        return self::paginated($cities);
    }
    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city = $city->load('country');
        return self::success([$city]);
    }
}
