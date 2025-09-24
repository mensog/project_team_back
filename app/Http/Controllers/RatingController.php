<?php

namespace App\Http\Controllers;

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
    protected RatingServiceInterface $ratingService;

    public function __construct(RatingServiceInterface $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(RatingResource::collection($this->ratingService->all()));
    }

    public function leaderboard(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->ratingService->getLeaderboard($perPage),
            UserResource::class
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request): JsonResponse
    {
        $ratingData = $request->validated();
        $rating = $this->ratingService->create($ratingData);

        return $this->messageResponse('Оценка успешно создана!', 201, [
            'data' => new RatingResource($rating),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating): JsonResponse
    {
        return $this->successResponse(new RatingResource($rating));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating): JsonResponse
    {
        $ratingData = $request->validated();
        $rating = $this->ratingService->update($rating->id, $ratingData);

        return $this->messageResponse('Оценка успешно обновлена!', 200, [
            'data' => new RatingResource($rating),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating): JsonResponse
    {
        $this->ratingService->delete($rating->id);

        return $this->messageResponse('Оценка удалена.', 200);
    }
}
