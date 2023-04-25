<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountWithdraw, User};

class BankAccountWithdrawPolicy
{
    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    public function update(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

    public function delete(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

}
