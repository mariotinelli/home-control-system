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
        return $user->hasPermissionTo('couple_spending_category_read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('couple_spending_category_create');
    }

    public function update(User $user, CoupleSpendingCategory $coupleSpendingCategory): bool
    {
        return $user->hasPermissionTo('couple_spending_category_update');
    }

    public function delete(User $user, CoupleSpendingCategory $coupleSpendingCategory): bool
    {
        return $user->hasPermissionTo('couple_spending_category_delete');
    }
}
