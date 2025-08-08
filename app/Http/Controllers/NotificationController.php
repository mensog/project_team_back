<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationReadRequest;
use App\Services\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $response = $this->notificationService->getUserNotifications(auth()->id(), $perPage);

        return response()->json($response);
    }

    public function markAsRead(NotificationReadRequest $request): JsonResponse
    {
        $this->notificationService->markAsRead($request->route('uuid'), auth()->id());

        return response()->json(['message' => 'Уведомление помечено как прочитанное']);
    }
}
