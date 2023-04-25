<?php

namespace App\Policies;

use App\Models\{MarketItemCategory, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketItemCategoryPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, MarketItemCategory $marketItemCategory): bool
    {
        return $marketItemCategory->marketItems()->count() === 0;
    }

}
