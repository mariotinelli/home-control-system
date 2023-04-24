<?php

namespace App\Policies;

use App\Models\{Market, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class MarketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Market $market): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Market $market): bool
    {
    }

    public function delete(User $user, Market $market): bool
    {
    }

    public function restore(User $user, Market $market): bool
    {
    }

    public function forceDelete(User $user, Market $market): bool
    {
    }
}
