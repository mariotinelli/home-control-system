<?php

namespace App\Policies;

use App\Models\{GoalEntry, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, GoalEntry $goalEntry): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, GoalEntry $goalEntry): bool
    {
    }

    public function delete(User $user, GoalEntry $goalEntry): bool
    {
    }

    public function restore(User $user, GoalEntry $goalEntry): bool
    {
    }

    public function forceDelete(User $user, GoalEntry $goalEntry): bool
    {
    }
}
