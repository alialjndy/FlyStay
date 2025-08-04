<?php

namespace App\Http\Controllers;

use App\Http\Requests\Airport\FilterAirportRequest;
use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FilterAirportRequest $request)
    {
        $this->authorize('viewAny',Airport::class);
        $airports = Airport::with(['city','country'])->Filter($request->validated()['countryName'] ?? null)->paginate(10);
        return self::paginated($airports);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airport $airport)
    {
        $this->authorize('view',$airport);
        $airport = $airport->load(['city','country']);
        return self::success([$airport]);
    }
}
