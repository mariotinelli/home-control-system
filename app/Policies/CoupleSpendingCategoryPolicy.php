<?php

namespace App\Policies;

use App\Models\{CoupleSpendingCategory, User};
use Illuminate\Auth\Access\HandlesAuthorization;

class CoupleSpendingCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_category_read');
    }

    public function view(User $user, CoupleSpendingCategory $coupleSpendingCategory): bool
    {
        return $user->hasPermissionTo('couple_spending_category_read')
            && $user->id === $coupleSpendingCategory->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_category_create');
    }

    public function update(User $user, CoupleSpendingCategory $coupleSpendingCategory): bool
    {
        return $user->hasPermissionTo('couple_spending_category_update')
            && $user->id === $coupleSpendingCategory->user_id;
    }

    public function delete(User $user, CoupleSpendingCategory $coupleSpendingCategory): bool
    {
        return $user->hasPermissionTo('couple_spending_category_delete')
            && $user->id === $coupleSpendingCategory->user_id
            && $coupleSpendingCategory->spendings()->count() === 0;
    }
}
