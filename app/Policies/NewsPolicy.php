<?php

namespace App\Policies;

use App\Models\User;
use App\Models\News;
use Illuminate\Auth\Access\Response;

class NewsPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, News $news)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для создания новостей.');
    }

    public function update(User $user, News $news)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для обновления этой новости.');
    }

    public function delete(User $user, News $news)
    {
        return $user->is_admin ? Response::allow() : Response::deny('У вас нет прав для удаления этой новости.');
    }
}
