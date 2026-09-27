<?php

namespace App\Policies;

use App\Models\OperationsJob;
use App\Models\User;

class OperationsJobPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->inDepartment('operations');
    }

    public function view(User $user, OperationsJob $job): bool
    {
        return $user->inDepartment('operations');
    }

    public function create(User $user): bool
    {
        return $user->inDepartment('operations');
    }

    public function update(User $user, OperationsJob $job): bool
    {
        return $user->isAdmin() || $job->created_by === $user->id;
    }

    public function delete(User $user, OperationsJob $job): bool
    {
        return $user->isAdmin() || $job->created_by === $user->id;
    }
}
