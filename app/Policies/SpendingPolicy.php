<?php

namespace App\Policies;

use App\Models\CreditCard;
use App\Models\Spending;
use App\Models\User;

class SpendingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Spending $spending): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, CreditCard $creditCard): bool
    {
        return $user->id == $creditCard->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Spending $spending): bool
    {
        return $user->id == $spending->creditCard->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Spending $spending): bool
    {
        return $user->id == $spending->creditCard->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Spending $spending): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Spending $spending): bool
    {
        //
    }
}
