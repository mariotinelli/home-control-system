<?php

namespace App\Policies;

use App\Models\BankAccount;
use App\Models\BankAccountEntry;
use App\Models\User;

class BankAccountEntryPolicy
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
    public function view(User $user, BankAccountEntry $bankAccountEntry): bool
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
    public function update(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        return $user->id === $bankAccountEntry->bankAccount->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BankAccountEntry $bankAccountEntry): bool
    {
        //
    }
}