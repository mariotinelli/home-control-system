<?php

namespace App\Policies;

use App\Models\InvestmentWithdraw;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvestmentWithdrawPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, InvestmentWithdraw $investmentWithdrawals): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, InvestmentWithdraw $investmentWithdrawals): bool
    {
    }

    public function delete(User $user, InvestmentWithdraw $investmentWithdrawals): bool
    {
    }

    public function restore(User $user, InvestmentWithdraw $investmentWithdrawals): bool
    {
    }

    public function forceDelete(User $user, InvestmentWithdraw $investmentWithdrawals): bool
    {
    }
}
