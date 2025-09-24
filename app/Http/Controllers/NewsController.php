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
    protected NewsServiceInterface $newsService;

    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->newsService->allPublic($perPage),
            NewsResource::class
        );
    }

    public function store(StoreNewsRequest $request): JsonResponse
    {
        $news = $this->newsService->create($request->validated());

        return $this->messageResponse('Новость успешно создана!', 201, [
            'data' => new NewsResource($news),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(new NewsResource($this->newsService->findPublic($id)));
    }

    public function update(UpdateNewsRequest $request, int $id): JsonResponse
    {
        $news = $this->newsService->update($id, $request->validated());

        return $this->messageResponse('Новость успешно обновлена!', 200, [
            'data' => new NewsResource($news),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->newsService->delete($id);

        return $this->messageResponse("Новость с ID:$id удалена.");
    }

    public function byStatus(Request $request, string $status): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->newsService->byStatus($status, $perPage),
            NewsResource::class
        );
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $news = $this->newsService->update($id, [
            'preview_image' => $request->file('preview_image'),
        ]);

        return $this->messageResponse('Превью успешно загружено!', 200, [
            'data' => new NewsResource($news),
        ]);
    }
}
