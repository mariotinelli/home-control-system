<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountEntry, User};

class BankAccountEntryPolicy
{
    public function viewAny(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank_account_entry_read');
    }

    public function view(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->hasPermissionTo('bank_account_entry_read');
    }

    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id
            && $user->hasPermissionTo('bank_account_entry_create')
            && ($bankAccount->entries()->count() === 0 && $bankAccount->withdrawals()->count() === 0);
    }

    public function update(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id
            && $user->hasPermissionTo('bank_account_entry_update');
    }

    public function delete(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id
            && $user->hasPermissionTo('bank_account_entry_delete');
    }
}
