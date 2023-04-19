<?php

namespace App\Policies;

use App\Models\MarketItemCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketItemCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, MarketItemCategory $marketItemCategory): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, MarketItemCategory $marketItemCategory): bool
    {
    }

    public function delete(User $user, MarketItemCategory $marketItemCategory): bool
    {
        return ($marketItemCategory->marketItems()->count() === 0);
    }

    public function restore(User $user, MarketItemCategory $marketItemCategory): bool
    {
    }

    public function forceDelete(User $user, MarketItemCategory $marketItemCategory): bool
    {
    }
}
