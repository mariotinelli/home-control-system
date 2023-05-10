<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountWithdraw, User};

class BankAccountWithdrawPolicy
{
    public function viewAny(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_read');
    }

    public function view(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_read');
    }

    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id
            && $user->hasPermissionTo('bank_account_withdraw_create');
    }

    public function update(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id
            && $user->hasPermissionTo('bank_account_withdraw_update');
    }

    public function delete(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id
            && $user->hasPermissionTo('bank_account_withdraw_delete');
    }

}
