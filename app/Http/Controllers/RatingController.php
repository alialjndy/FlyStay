<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rating\CreateRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Services\Rating\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $ratingService ;
    public function __construct(RatingService $ratingService){
        $this->ratingService = $ratingService ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny',Rating::class);
        $allRatings = $this->ratingService->getAllRating();
        return self::paginated($allRatings , RatingResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRatingRequest $request)
    {
        $this->authorize('create', Rating::class);
        $info = $this->ratingService->createOrUpdateRating($request->validated());
        return self::success([new RatingResource($info['data'])],$info['code']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        $this->authorize('view',$rating);
        $rating->load(['user','hotel']);
        return self::success([new RatingResource($rating)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $this->authorize('update' , $rating);
        $updatedRating = $this->ratingService->updateRating($request->validated() , $rating);
        return self::success([new RatingResource($updatedRating)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        $this->authorize('delete' , $rating);
        $rating->delete();
        return self::success([]);
    }
}
