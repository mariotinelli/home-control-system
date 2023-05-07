<?php

namespace App\Policies;

use App\Models\{CoupleSpending, User};

class CoupleSpendingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_delete');
    }
}
