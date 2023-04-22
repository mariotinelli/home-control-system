<?php

namespace App\Policies;

use App\Models\Investment;
use App\Models\InvestmentEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestmentEntryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, InvestmentEntry $investmentEntry): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, InvestmentEntry $investmentEntry, Investment $investment): bool
    {
        return $investmentEntry->investment_id === $investment->id;
    }

    public function delete(User $user, InvestmentEntry $investmentEntry): bool
    {
    }

    public function restore(User $user, InvestmentEntry $investmentEntry): bool
    {
    }

    public function forceDelete(User $user, InvestmentEntry $investmentEntry): bool
    {
    }
}
