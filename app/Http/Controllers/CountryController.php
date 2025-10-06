<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\FilterCountryRequest;
use App\Models\Country;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use ManageCache;
    /**
     * Display a listing of the resource.
     */
    public function index(FilterCountryRequest $request)
    {
        $this->authorize('viewAny',Country::class);
        $cacheKey = 'countries_'.md5(json_encode($request->validated()));

        $countries = $this->getFromCache('countries' , $cacheKey) ??
            $this->addToCache(
                'countries',
                $cacheKey ,
                Country::filter($request->validated())->with(['cities','cities.airports','cities.hotels'])->paginate(10) ,
                now()->addDays(15));

        return self::paginated($countries);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        $cacheKey = 'country_'.$country->id;
        $country = $this->getFromCache('countries',$cacheKey) ??
            $this->addToCache(
                'countries',
                $cacheKey ,
                $country->load(['cities','cities.airports','cities.hotels']) ,
                now()->addDays(15));

        return self::success([$country]);
    }

}
