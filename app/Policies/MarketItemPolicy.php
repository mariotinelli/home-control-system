<?php

namespace App\Policies;

use App\Models\{MarketItem, User};

class MarketItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_item_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MarketItem $marketItem): bool
    {
        return $user->hasPermissionTo('market_item_read')
            && $user->id === $marketItem->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_item_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MarketItem $marketItem): bool
    {
        return $user->hasPermissionTo('market_item_update')
            && $user->id === $marketItem->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MarketItem $marketItem): bool
    {
        return $user->hasPermissionTo('market_item_delete')
            && $user->id === $marketItem->user_id;
    }

}
