<?php

namespace App\Policies;

use App\Models\{CreditCard, User};

class CreditCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('credit_card_read');
    }

    public function view(User $user, CreditCard $creditCard): bool
    {
        return $user->hasPermissionTo('credit_card_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('credit_card_create');
    }

    public function update(User $user, CreditCard $creditCard): bool
    {
        return $user->hasPermissionTo('credit_card_update') && $creditCard->user_id === $user->id;
    }

    public function delete(User $user, CreditCard $creditCard): bool
    {
        return $user->hasPermissionTo('credit_card_delete') && $creditCard->user_id === $user->id;
    }

}
