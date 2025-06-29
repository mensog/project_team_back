<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(?User $user): Response
    {
        return $user ? Response::allow() : Response::deny('У вас нет прав для просмотра проектов.');
    }

    public function viewAnyForUser(User $user, int $userId): bool
    {
        return $user->id === $userId || $user->is_admin;
    }

    public function view(User $user, Project $project): Response
    {
        return $user->is_admin || $user->id === $project->user_id || $project->participants->contains($user->id)
            ? Response::allow()
            : Response::deny('У вас нет прав для просмотра этого проекта.');
    }

    public function create(?User $user): Response
    {
        return $user ? Response::allow() : Response::deny('У вас нет прав для создания проектов.');
    }

    public function update(User $user, Project $project): Response
    {
        return $user->is_admin || $user->id === $project->user_id
            ? Response::allow()
            : Response::deny('У вас нет прав для обновления этого проекта.');
    }

    public function delete(User $user, Project $project): Response
    {
        return $user->is_admin
            ? Response::allow()
            : Response::deny('У вас нет прав для удаления этого проекта.');
    }

    public function join(User $user, Project $project): Response
    {
        return !$project->participants->contains($user->id)
            ? Response::allow()
            : Response::deny('Вы уже участник проекта.');
    }

    public function leave(User $user, Project $project): Response
    {
        return $project->participants->contains($user->id)
            ? Response::allow()
            : Response::deny('Вы не можете покинуть проект, так как не являетесь его участником.');
    }
}
