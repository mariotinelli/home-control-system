<?php

namespace App\Policies;

use App\Models\{GoalEntry, User};

class GoalEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('goal_entry_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GoalEntry $goalEntry): bool
    {
        return $user->hasPermissionTo('goal_entry_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('goal_entry_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GoalEntry $goalEntry): bool
    {
        return $user->hasPermissionTo('goal_entry_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GoalEntry $goalEntry): bool
    {
        return $user->hasPermissionTo('goal_entry_delete');
    }
}
