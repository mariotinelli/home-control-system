<?php

namespace App\Policies;

use App\Models\{CreditCard, Spending, User};

class SpendingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('credit_card_spending_read');
    }

    public function view(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_read')
            && $user->id === $creditCardSpending->creditCard->user_id;
    }

    public function create(User $user, CreditCard $creditCard): bool
    {
        return $user->hasPermissionTo('credit_card_spending_create')
            && $user->id === $creditCard->user_id;
    }

    public function update(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_update')
            && $user->id === $creditCardSpending->creditCard->user_id;
    }

    public function delete(User $user, Spending $creditCardSpending): bool
    {
        return $user->hasPermissionTo('credit_card_spending_delete')
            && $user->id === $creditCardSpending->creditCard->user_id;
    }
}
