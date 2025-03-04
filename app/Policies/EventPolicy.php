<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view events.');
    }

    public function view(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view this event.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create events.');
    }

    public function update(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to update this event.');
    }

    public function delete(User $user, Event $event)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this event.');
    }
}
