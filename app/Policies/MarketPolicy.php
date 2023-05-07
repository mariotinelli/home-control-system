<?php

namespace App\Policies;

use App\Models\{Market, User};

class MarketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Market $market): bool
    {
        return $user->hasPermissionTo('market_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Market $market): bool
    {
        return $user->hasPermissionTo('market_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Market $market): bool
    {
        return $user->hasPermissionTo('market_delete');
    }
}
