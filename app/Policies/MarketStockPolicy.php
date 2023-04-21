<?php

namespace App\Policies;

use App\Models\MarketStock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketStockPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, MarketStock $marketStock): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, MarketStock $marketStock): bool
    {
        return ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }

    public function delete(User $user, MarketStock $marketStock): bool
    {
        return ($marketStock->entries()->count() == 0) && ($marketStock->withdrawals()->count() === 0);
    }

    public function restore(User $user, MarketStock $marketStock): bool
    {
    }

    public function forceDelete(User $user, MarketStock $marketStock): bool
    {
    }
}
