<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationReadRequest;
use App\Http\Resources\NotificationResource;
use App\Services\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationServiceInterface $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        return $this->paginatedResponse(
            $this->notificationService->getUserNotifications(auth()->id(), $perPage),
            NotificationResource::class
        );
    }

    public function markAsRead(NotificationReadRequest $request): JsonResponse
    {
        $this->notificationService->markAsRead($request->route('uuid'), auth()->id());

        return $this->messageResponse('Уведомление помечено как прочитанное');
    }
}
