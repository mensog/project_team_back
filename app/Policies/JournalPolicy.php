<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Journal;
use Illuminate\Auth\Access\Response;

class JournalPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view journal entries.');
    }

    public function view(User $user, Journal $journal)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to view this journal entry.');
    }

    public function create(User $user)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to create journal entries.');
    }

    public function update(User $user, Journal $journal)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to update this journal entry.');
    }

    public function delete(User $user, Journal $journal)
    {
        return $user->is_admin ? Response::allow() : Response::deny('You do not have permission to delete this journal entry.');
    }
}
