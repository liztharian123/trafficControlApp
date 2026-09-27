<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuotePolicy
{
 public function viewAny(User $user): bool
    {
        return $user->inDepartment('quote');
    }

    public function view(User $user, Quote $quote): bool
    {
        return $user->inDepartment('quote');
    }

    public function create(User $user): bool
    {
        return $user->inDepartment('quote');
    }

    public function update(User $user, Quote $quote): bool
    {
        return $user->isAdmin() || $quote->created_by === $user->id;
    }

    public function delete(User $user, Quote $quote): bool
    {
        return $user->isAdmin() || $quote->created_by === $user->id;
    }
}
