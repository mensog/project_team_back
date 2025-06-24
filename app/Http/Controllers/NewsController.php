<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Requests\News\UploadPreviewRequest;
use App\Http\Resources\NewsResource;
use App\Services\Interfaces\NewsServiceInterface;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(): JsonResponse
    {
        return response()->json(NewsResource::collection($this->newsService->all()));
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        $news = $this->newsService->create($request->validated());
        return response()->json(['data' => new NewsResource($news)], 201);
    }

    public function show(int $id): JsonResponse
    {
        $news = $this->newsService->find($id);
        return response()->json(['data' => new NewsResource($news)]);
    }

    public function update(UpdateNewsRequest $request, int $id): JsonResponse
    {
        $news = $this->newsService->update($id, $request->validated());
        return response()->json(['data' => new NewsResource($news)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->newsService->delete($id);
        return response()->json(null, 204);
    }

    public function byStatus(string $status): JsonResponse
    {
        $news = $this->newsService->byStatus($status);
        return response()->json(NewsResource::collection($news));
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $news = $this->newsService->update($id, [
            'preview_image' => $request->file('preview_image')->store('news_previews', 'public')
        ]);
        return response()->json([
            'message' => 'Превью успешно загружено!',
            'data' => new NewsResource($news)
        ]);
    }
}
