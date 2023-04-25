<?php

namespace App\Policies;

use App\Models\{CreditCard, User};

class CreditCardPolicy
{
    public function update(User $user, CreditCard $creditCard): bool
    {
        return $creditCard->user_id === $user->id;
    }

    public function delete(User $user, CreditCard $creditCard): bool
    {
        return $creditCard->user_id === $user->id;
    }

}
