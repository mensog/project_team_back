<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewAny(?User $user): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для просмотра проектов.');
    }

    public function viewAnyForUser(User $user, int $targetUserId): bool
    {
        return $user->is_admin || $user->id === $targetUserId;
    }

    public function view(User $user, Project $project): Response
    {
        return $user->is_admin || $project->user_id === $user->id || $project->participants()->where('user_id', $user->id)->exists()
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

    public function approve(User $user, Project $project): Response
    {
        return $user->is_admin
            ? Response::allow()
            : Response::deny('Только администратор может подтверждать проекты.');
    }

    public function reject(User $user, Project $project): Response
    {
        return $user->is_admin
            ? Response::allow()
            : Response::deny('Только администратор может отклонять проекты.');
    }

    public function uploadCertificate(User $user, Project $project): Response
    {
        return $project->status === 'completed' && ($user->is_admin || $project->participants->contains($user->id))
            ? Response::allow()
            : Response::deny('Вы можете загружать сертификат только для завершённых проектов, в которых вы участник.');
    }
}
