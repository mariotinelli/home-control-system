<?php

namespace App\Policies;

use App\Models\{Investment, InvestmentEntry, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestmentEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('investment_entry_read');
    }

    public function view(User $user, Investment $investment): bool
    {
        return $user->hasPermissionTo('investment_entry_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('investment_entry_create');
    }

    public function update(User $user, InvestmentEntry $investmentEntry, Investment $investment): bool
    {
        return $user->hasPermissionTo('investment_entry_update') && $investmentEntry->investment_id === $investment->id;
    }

    public function delete(User $user, InvestmentEntry $investmentEntry): bool
    {
        return $user->hasPermissionTo('investment_entry_delete');
    }

}
