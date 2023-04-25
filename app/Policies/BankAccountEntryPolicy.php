<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountEntry, User};

class BankAccountEntryPolicy
{
    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    public function update(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id;
    }

    public function delete(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id;
    }
}
