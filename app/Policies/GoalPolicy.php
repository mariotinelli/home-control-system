<?php

namespace App\Policies;

use App\Models\{Goal, User};

class GoalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('goal_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Goal $goal): bool
    {
        return $user->hasPermissionTo('goal_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('goal_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Goal $goal): bool
    {
        return $user->hasPermissionTo('goal_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Goal $goal): bool
    {
        return $user->hasPermissionTo('goal_delete');
    }

}
