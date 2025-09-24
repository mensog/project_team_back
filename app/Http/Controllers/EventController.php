<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Requests\Event\UploadPreviewRequest;
use App\Http\Resources\EventResource;
use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected EventServiceInterface $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->eventService->all($perPage),
            EventResource::class
        );
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = $this->eventService->create($request->validated());

        return $this->messageResponse('Событие успешно создано!', 201, [
            'data' => new EventResource($event),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(new EventResource($this->eventService->find($id)));
    }

    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, $request->validated());

        return $this->messageResponse('Событие успешно обновлено!', 200, [
            'data' => new EventResource($event),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->eventService->delete($id);

        return $this->messageResponse("Событие с ID:$id удалено.");
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, [
            'preview_image' => $request->file('preview_image'),
        ]);

        return $this->messageResponse('Превью успешно загружено!', 200, [
            'data' => new EventResource($event),
        ]);
    }
}
