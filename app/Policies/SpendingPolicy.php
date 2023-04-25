<?php

namespace App\Policies;

use App\Models\{CreditCard, Spending, User};

class SpendingPolicy
{
    public function create(User $user, CreditCard $creditCard): bool
    {
        return $user->id == $creditCard->user_id;
    }

    public function update(User $user, Spending $spending): bool
    {
        return $user->id == $spending->creditCard->user_id;
    }

    public function delete(User $user, Spending $spending): bool
    {
        return $user->id == $spending->creditCard->user_id;
    }

}
