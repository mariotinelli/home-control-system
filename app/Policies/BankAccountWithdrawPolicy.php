<?php

namespace App\Policies;

use App\Models\{BankAccount, BankAccountWithdraw, User};

class BankAccountWithdrawPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, BankAccount $bankAccount): bool
    {
        return $user->id === $bankAccount->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        return $user->id === $bankAccountWithdraw->bankAccount->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BankAccountWithdraw $bankAccountWithdraw): bool
    {
        //
    }
}
