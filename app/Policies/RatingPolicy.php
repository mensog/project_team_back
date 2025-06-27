<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Auth\Access\Response;

class RatingPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Rating $rating)
    {
        return $user->is_admin;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, Rating $rating)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Rating $rating)
    {
        return $user->is_admin;
    }
}
