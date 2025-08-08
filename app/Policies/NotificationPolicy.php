<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function update(User $user, DatabaseNotification $notification): bool
    {
        return $user->id === $notification->notifiable_id;
    }
}
