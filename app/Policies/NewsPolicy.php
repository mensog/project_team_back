<?php

namespace App\Policies;

use App\Models\User;
use App\Models\News;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view news.');
    }

    public function view(User $user, News $news)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view this news.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create news.');
    }

    public function update(User $user, News $news)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to update this news.');
    }

    public function delete(User $user, News $news)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this news.');
    }
}
