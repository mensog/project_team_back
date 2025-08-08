<?php

namespace App\Repositories;

use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class NotificationRepository implements NotificationRepositoryInterface
{
    protected $model;

    public function __construct(DatabaseNotification $model)
    {
        $this->model = $model;
    }

    public function getByUser(int $userId, int $perPage): LengthAwarePaginator
    {
        return $this->model->where('notifiable_id', $userId)
            ->where('notifiable_type', \App\Models\User::class)
            ->paginate($perPage);
    }

    public function findByIdForUser(string $id, int $userId): ?DatabaseNotification
    {
        $notification = $this->model->where('id', $id)
            ->where('notifiable_id', $userId)
            ->where('notifiable_type', \App\Models\User::class)
            ->first();

        if (!$notification) {
            Log::warning('Не найдено уведомление для пользователя', [
                'notification_id' => $id,
                'user_id' => $userId,
            ]);
        }

        return $notification;
    }

    public function markAsRead(DatabaseNotification $notification): void
    {
        $notification->markAsRead();
        Log::info('Уведомление помечено как прочитанное', [
            'notification_id' => $notification->id,
            'user_id' => $notification->notifiable_id,
        ]);
    }
}
