<?php

namespace App\Policies;

use App\Models\{CoupleSpending, User};

class CoupleSpendingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_read');
    }

    public function view(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_read')
            && $user->id === $coupleSpending->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_create');
    }

    public function update(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_update')
            && $user->id === $coupleSpending->user_id;
    }

    public function delete(User $user, CoupleSpending $coupleSpending): bool
    {
        return $user->hasPermissionTo('couple_spending_delete')
            && $user->id === $coupleSpending->user_id;
    }
}
