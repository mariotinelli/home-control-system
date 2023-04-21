<?php

namespace App\Policies;

use App\Models\MarketStockEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketStockEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, MarketStockEntry $marketStockEntry): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, MarketStockEntry $marketStockEntry): bool
    {
    }

    public function delete(User $user, MarketStockEntry $marketStockEntry): bool
    {
    }

    public function restore(User $user, MarketStockEntry $marketStockEntry): bool
    {
    }

    public function forceDelete(User $user, MarketStockEntry $marketStockEntry): bool
    {
    }
}
