<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function viewAnyForUser(User $user, int $userId): bool
    {
        return $user->id === $userId || $user->is_admin;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->id === $certificate->user_id || $user->is_admin;
    }

    public function update(User $user, Certificate $certificate): bool
    {
        return $user->id === $certificate->user_id || $user->is_admin;
    }
}
