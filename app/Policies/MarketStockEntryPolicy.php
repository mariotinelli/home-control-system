<?php

namespace App\Policies;

use App\Models\{MarketStockEntry, User};

class MarketStockEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_entry_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MarketStockEntry $marketStockEntry): bool
    {
        return $user->hasPermissionTo('market_stock_entry_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_entry_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MarketStockEntry $marketStockEntry): bool
    {
        return $user->hasPermissionTo('market_stock_entry_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MarketStockEntry $marketStockEntry): bool
    {
        return $user->hasPermissionTo('market_stock_entry_delete');
    }
}
