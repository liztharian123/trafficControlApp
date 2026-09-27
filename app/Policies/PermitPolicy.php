<?php

namespace App\Policies;

use App\Models\Permit;
use App\Models\User;

class PermitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->inDepartment('permit');
    }

    public function view(User $user, Permit $permit): bool
    {
        return $user->inDepartment('permit');
    }

    public function create(User $user): bool
    {
        return $user->inDepartment('permit');
    }

    public function update(User $user, Permit $permit): bool
    {
        return $user->isAdmin() || $permit->created_by === $user->id;
    }

    public function delete(User $user, Permit $permit): bool
    {
        return $user->isAdmin() || $permit->created_by === $user->id;
    }
}
