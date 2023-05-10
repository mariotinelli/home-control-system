<?php

namespace App\Policies;

use App\Models\{BankAccount, User};

class BankAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('bank_account_read');
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank_account_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('bank_account_create');
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return ($user->id === $bankAccount->user_id)
            && ($bankAccount->entries()->count() === 0 && $bankAccount->withdrawals()->count() === 0)
            && $user->hasPermissionTo('bank_account_update');
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return ($user->id === $bankAccount->user_id)
            && ($bankAccount->entries()->count() === 0 && $bankAccount->withdrawals()->count() === 0)
            && $user->hasPermissionTo('bank_account_delete');
    }

}
