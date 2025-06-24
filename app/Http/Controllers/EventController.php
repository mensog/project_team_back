<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Requests\Event\UploadPreviewRequest;
use App\Http\Resources\EventResource;
use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(): JsonResponse
    {
        return response()->json(EventResource::collection($this->eventService->all()));
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
        return response()->json(null, 204);
    }

    public function uploadPreview(UploadPreviewRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->update($id, [
            'preview_image' => $request->file('preview_image')->store('event_previews', 'public')
        ]);
        return response()->json([
            'message' => 'Превью успешно загружено!',
            'data' => new EventResource($event)
        ]);
    }
}
