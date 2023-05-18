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
        return $user->hasPermissionTo('bank_account_read')
            && $this->isOwnerOrAdmin($user, $bankAccount);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('bank_account_create');
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $this->isOwnerOrAdmin($user, $bankAccount)
            && ($this->notChangeBalance($bankAccount) || $this->emptyTransactions($bankAccount))
            && $user->hasPermissionTo('bank_account_update');
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $this->isOwnerOrAdmin($user, $bankAccount)
            && $user->hasPermissionTo('bank_account_delete');
    }

    private function isOwnerOrAdmin(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id || $user->isAdmin();
    }

    private function notChangeBalance(BankAccount $bankAccount): bool
    {
        return $bankAccount->balance == $bankAccount->getOriginal('balance');
    }

    private function emptyTransactions(BankAccount $bankAccount): bool
    {
        return $this->emptyEntries($bankAccount) && $this->emptyWithdrawals($bankAccount);
    }

    private function emptyEntries(BankAccount $bankAccount): bool
    {
        return $bankAccount->entries()->count() === 0;
    }

    private function emptyWithdrawals(BankAccount $bankAccount): bool
    {
        return $bankAccount->withdrawals()->count() === 0;
    }

}
