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
    protected $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $events = $this->eventService->all($perPage);
        return response()->json([
            'data' => EventResource::collection($events),
            'meta' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ],
        ]);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = $this->eventService->create($request->validated());
        return response()->json(['data' => new EventResource($event)], 201);
    }

    public function show(int $id): JsonResponse
    {
        $event = $this->eventService->find($id);
        return response()->json(['data' => new EventResource($event)]);
    }

    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, $request->validated());
        return response()->json(['data' => new EventResource($event)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->eventService->delete($id);
        return response()->json([
            'message' => "Событие с ID:$id удалено."
        ], 200);
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, [
            'preview_image' => $request->file('preview_image')
        ]);
        return response()->json([
            'message' => 'Превью успешно загружено!',
            'data' => new EventResource($event)
        ]);
    }
}
