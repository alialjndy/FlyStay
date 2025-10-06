<?php

namespace App\Http\Controllers;

use App\Http\Requests\Airport\FilterAirportRequest;
use App\Models\Airport;
use App\Traits\ManageCache;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    use ManageCache ;
    /**
     * Display a listing of the resource.
     */
    public function index(FilterAirportRequest $request)
    {
        $this->authorize('viewAny',Airport::class);
        $cacheKey = 'all_airports_' . data_get($request->validated(), 'countryName', 'all');

        $airports = $this->getFromCache('airports' , $cacheKey) ??
            $this->addToCache(
                'airports',
                $cacheKey,
                Airport::Filter($request->countryName ?? null)->with(['city','country'])->paginate(10) ,
                now()->addDays(30)
            );

        return self::paginated($airports);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airport $airport)
    {
        $this->authorize('view',$airport);
        $cacheKey = 'airport_'.$airport->id ;

        $airport = $this->getFromCache('airports',$cacheKey) ??
            $this->addToCache(
                'airports',
                $cacheKey,
                $airport->load(['city','country']),
                now()->addDays(30)
            );

        return self::success([$airport]);
    }
}
