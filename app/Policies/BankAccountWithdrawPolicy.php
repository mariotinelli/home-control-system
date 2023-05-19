<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountWithdraw, User};

class BankAccountWithdrawPolicy
{
    public function viewAny(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_read')
            && $user->id === $bankAccount->user_id;
    }

    public function view(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_read')
            && $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_create')
            && $user->id === $bankAccount->user_id;
    }

    public function update(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_update')
            && $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

    public function delete(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->hasPermissionTo('bank_account_withdraw_delete')
            && $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

}
