<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $airports = Airport::with(['city','country'])->paginate(10);
        return self::paginated($airports);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airport $airport)
    {
        $airport = $airport->load(['city','country']);
        return self::success([$airport]);
    }
}
