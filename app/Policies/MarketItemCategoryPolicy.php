<?php

namespace App\Policies;

use App\Models\{MarketItemCategory, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketItemCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('market_item_category_read');
    }

    public function view(User $user, MarketItemCategory $marketItemCategory): bool
    {
        return $user->hasPermissionTo('market_item_category_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('market_item_category_create');
    }

    public function update(User $user, MarketItemCategory $marketItemCategory): bool
    {
        return $user->hasPermissionTo('market_item_category_update');
    }

    public function delete(User $user, MarketItemCategory $marketItemCategory): bool
    {
        return $user->hasPermissionTo('market_item_category_delete') && $marketItemCategory->marketItems()->count() === 0;
    }

}
