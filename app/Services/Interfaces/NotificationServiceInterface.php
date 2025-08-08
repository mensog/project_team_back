<?php

namespace App\Services\Interfaces;

interface NotificationServiceInterface
{
    public function getUserNotifications(int $userId, int $perPage): array;
    public function markAsRead(string $id, int $userId): void;
}
