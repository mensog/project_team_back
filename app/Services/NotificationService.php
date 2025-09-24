<?php

namespace App\Services;

use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Services\Interfaces\NotificationServiceInterface;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class NotificationService implements NotificationServiceInterface
{
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getUserNotifications(int $userId, int $perPage): LengthAwarePaginator
    {
        Gate::authorize('viewAny', DatabaseNotification::class);

        return $this->notificationRepository->getByUser($userId, $perPage);
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
