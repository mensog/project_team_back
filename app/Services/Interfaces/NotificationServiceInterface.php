<?php

namespace App\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationServiceInterface
{
    public function getUserNotifications(int $userId, int $perPage): LengthAwarePaginator;
    public function markAsRead(string $id, int $userId): void;
}
