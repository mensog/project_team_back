<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Journal $journal)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, Journal $journal = null)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Journal $journal = null)
    {
        return $user->is_admin;
    }
}
