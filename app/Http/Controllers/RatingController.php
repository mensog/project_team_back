<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Http\Requests\Rating\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Http\Resources\UserResource;
use App\Models\Rating;
use App\Services\Interfaces\RatingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingServiceInterface $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RatingResource::collection($this->ratingService->all());
    }

    public function leaderboard(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $users = $this->ratingService->getLeaderboard((int) $perPage);
        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $ratingData = $request->validated();
        $rating = $this->ratingService->create($ratingData);

        return new RatingResource($rating);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        return new RatingResource($rating);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $ratingData = $request->validated();
        $rating = $this->ratingService->update($rating->id, $ratingData);

        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        $this->ratingService->delete($rating->id);

        return response()->json(null, 204);
    }
}
