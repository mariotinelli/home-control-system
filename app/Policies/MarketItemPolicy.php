<?php

namespace App\Policies;

use App\Models\{MarketItem, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, MarketItem $marketItem): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, MarketItem $marketItem): bool
    {
    }

    public function delete(User $user, MarketItem $marketItem): bool
    {
    }

    public function restore(User $user, MarketItem $marketItem): bool
    {
    }

    public function forceDelete(User $user, MarketItem $marketItem): bool
    {
    }
}
