<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Event $event): bool
    {
        return true;
    }

    public function create(User $user): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для создания событий.');
    }

    public function update(User $user, Event $event): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для обновления этого события.');
    }

    public function delete(User $user, Event $event): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для удаления этого события.');
    }
}
