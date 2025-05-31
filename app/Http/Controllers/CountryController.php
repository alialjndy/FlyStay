<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\FilterCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterCountryRequest $request)
    {
        $countries = Country::filter($request)->with(['cities','cities.airports','cities.hotels'])->paginate(10);
        return self::paginated($countries);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        $country = $country->load(['cities','cities.airports','cities.hotels']);
        return self::success([$country]);
    }

}
