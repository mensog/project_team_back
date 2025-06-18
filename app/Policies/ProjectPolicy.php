<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view projects.');
    }

    public function viewAnyForUser(User $user, int $userId): bool
    {
        return $user->id === $userId || $user->is_admin; // Без изменений, но фильтрация в getByUser решает проблему
    }

    public function view(User $user, Project $project): Response
    {
        return $user->is_admin || $user->id === $project->user_id || $project->participants->contains($user->id)
            ? Response::allow()
            : Response::deny('You do not have permission to view this project.');
    }

    public function create(User $user): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create projects.');
    }

    public function update(User $user, Project $project): Response
    {
        return $user->is_admin || $user->id === $project->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this project.');
    }

    public function delete(User $user, Project $project): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this project.');
    }

    public function join(User $user, Project $project): bool
    {
        return !$project->participants->contains($user->id);
    }

    public function leave(User $user, Project $project): bool
    {
        return $project->participants->contains($user->id);
    }
}
