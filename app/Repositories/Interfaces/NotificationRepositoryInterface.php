<?php

namespace App\Repositories\Interfaces;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function getByUser(int $userId, int $perPage): LengthAwarePaginator;
    public function findByIdForUser(string $id, int $userId): ?DatabaseNotification;
    public function markAsRead(DatabaseNotification $notification): void;
}
