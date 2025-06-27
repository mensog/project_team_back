<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Http\Requests\News\UploadPreviewRequest;
use App\Http\Resources\NewsResource;
use App\Services\Interfaces\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $news = $this->newsService->allPublic($perPage);
        return response()->json([
            'data' => NewsResource::collection($news),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ],
        ]);
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        $news = $this->newsService->create($request->validated());
        return response()->json(['data' => new NewsResource($news)], 201);
    }

    public function show(int $id): JsonResponse
    {
        $news = $this->newsService->findPublic($id);
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
        return response()->json([
            'message' => "Новость с ID:$id удалена."
        ], 200);
    }

    public function byStatus(Request $request, string $status): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $news = $this->newsService->byStatus($status, $perPage);
        return response()->json([
            'data' => NewsResource::collection($news),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ],
        ]);
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
