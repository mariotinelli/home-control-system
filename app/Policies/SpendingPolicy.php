<?php

namespace App\Policies;

use App\Models\{Spending, User};

class SpendingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('credit_card_spending_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('credit_card_spending_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_delete');
    }
}
