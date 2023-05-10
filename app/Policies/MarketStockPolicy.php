<?php

namespace App\Policies;

use App\Models\{MarketStock, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketStockPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_read');
    }

    public function view(User $user, MarketStock $marketStock): bool
    {
        return $user->hasPermissionTo('market_stock_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_stock_create');
    }

    public function update(User $user, MarketStock $marketStock): bool
    {
        return $user->hasPermissionTo('market_stock_update') && ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }

    public function delete(User $user, MarketStock $marketStock): bool
    {
        return $user->hasPermissionTo('market_stock_delete') && ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }

    public function restore(User $user, MarketStock $marketStock): bool
    {
        return $user->hasPermissionTo('market_stock_restore');
    }

    public function forceDelete(User $user, MarketStock $marketStock): bool
    {
        return $user->hasPermissionTo('market_stock_force_delete');
    }
}
