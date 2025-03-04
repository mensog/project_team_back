<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view projects.');
    }

    public function view(User $user, Project $project)
    {
        return $user->is_admin || $user->id === $project->user_id ? Response::allow() : Response::deny('You do not have permission to view this project.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create projects.');
    }

    public function update(User $user, Project $project)
    {
        return $user->is_admin || $user->id === $project->user_id ? Response::allow() : Response::deny('You do not have permission to update this project.');
    }

    public function delete(User $user, Project $project)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this project.');
    }
}
