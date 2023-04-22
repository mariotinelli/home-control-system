<?php

namespace App\Policies;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Goal $goal): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Goal $goal): bool
    {
    }

    public function delete(User $user, Goal $goal): bool
    {
    }

    public function restore(User $user, Goal $goal): bool
    {
    }

    public function forceDelete(User $user, Goal $goal): bool
    {
    }
}
