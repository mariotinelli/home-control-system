<?php

namespace App\Policies;

use App\Models\{MarketStockWithdrawal, User};

class MarketStockWithdrawalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_withdraw_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MarketStockWithdrawal $marketStockWithdrawal): bool
    {
        return $user->hasPermissionTo('market_stock_withdraw_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_withdraw_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MarketStockWithdrawal $marketStockWithdrawal): bool
    {
        return $user->hasPermissionTo('market_stock_withdraw_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MarketStockWithdrawal $marketStockWithdrawal): bool
    {
        return $user->hasPermissionTo('market_stock_withdraw_delete');
    }
}
