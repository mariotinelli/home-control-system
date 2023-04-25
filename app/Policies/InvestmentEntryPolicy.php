<?php

namespace App\Policies;

use App\Models\{Investment, InvestmentEntry, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestmentEntryPolicy
{
    use HandlesAuthorization;

    public function update(User $user, InvestmentEntry $investmentEntry, Investment $investment): bool
    {
        return $investmentEntry->investment_id === $investment->id;
    }
}
