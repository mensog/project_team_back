<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): Response
    {
        return $user->id === $model->id || $user->is_admin ? Response::allow() : Response::deny('ُУ вас нет доступа к этому пользователю.');
    }

    public function create(User $user): Response
    {
        return $user->is_admin ? Response::allow() : Response::deny('ُУ вас нет прав на создание пользователей!');
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->is_admin;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->is_admin;
    }
}
