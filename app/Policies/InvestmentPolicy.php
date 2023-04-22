<?php

namespace App\Policies;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Investment $investment): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Investment $investment): bool
    {
    }

    public function delete(User $user, Investment $investment): bool
    {
    }

    public function restore(User $user, Investment $investment): bool
    {
    }

    public function forceDelete(User $user, Investment $investment): bool
    {
    }
}
