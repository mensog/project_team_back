<?php

namespace App\Services;

use App\Http\Resources\NotificationResource;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Services\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;

class NotificationService implements NotificationServiceInterface
{
    protected $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getUserNotifications(int $userId, int $perPage): array
    {
        Gate::authorize('viewAny', DatabaseNotification::class);

        $notifications = $this->notificationRepository->getByUser($userId, $perPage);

        return [
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ];
    }

    public function markAsRead(string $id, int $userId): void
    {
        $notification = $this->notificationRepository->findByIdForUser($id, $userId);
        if (!$notification) {
            abort(404, 'Уведомление не найдено');
        }

        Gate::authorize('update', $notification);
        $this->notificationRepository->markAsRead($notification);
    }
}
