<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view ratings.');
    }

    public function view(User $user, Rating $rating)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view this rating.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create ratings.');
    }

    public function update(User $user, Rating $rating)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to update this rating.');
    }

    public function delete(User $user, Rating $rating)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this rating.');
    }
}
