<?php

namespace App\Policies;

use App\Models\{BankAccount, User};

class BankAccountPolicy
{
    public function update(User $user, BankAccount $bankAccount): bool
    {
        return ($user->id === $bankAccount->user_id)
            && ($bankAccount->entries()->count() === 0 && $bankAccount->withdrawals()->count() === 0);
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return ($user->id === $bankAccount->user_id)
            && ($bankAccount->entries()->count() === 0 && $bankAccount->withdrawals()->count() === 0);
    }

}
