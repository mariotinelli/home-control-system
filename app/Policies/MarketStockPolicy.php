<?php

namespace App\Policies;

use App\Models\{MarketStock, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketStockPolicy
{
    use HandlesAuthorization;

    public function update(User $user, MarketStock $marketStock): bool
    {
        return ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }

    public function delete(User $user, MarketStock $marketStock): bool
    {
        return ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }
}
