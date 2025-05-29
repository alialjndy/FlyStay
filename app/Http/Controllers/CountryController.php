<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #TODO إنشاء فورم ريكويست
    public function index(Request $request)
    {
        $countries = Country::filter($request)->with('cities')->paginate(10);
        return self::paginated($countries);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        $country = $country->load('cities');
        return self::success([$country]);
    }

}
