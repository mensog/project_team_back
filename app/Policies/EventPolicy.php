<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для просмотра событий.');
    }

    public function view(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для просмотра этого события.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для создания событий.');
    }

    public function update(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для обновления этого события.');
    }

    public function delete(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для удаления этого события.');
    }
}
