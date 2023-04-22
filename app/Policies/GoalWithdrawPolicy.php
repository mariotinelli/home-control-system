<?php

namespace App\Policies;

use App\Models\GoalWithdraw;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalWithdrawPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, GoalWithdraw $goalWithdraw): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, GoalWithdraw $goalWithdraw): bool
    {
    }

    public function delete(User $user, GoalWithdraw $goalWithdraw): bool
    {
    }

    public function restore(User $user, GoalWithdraw $goalWithdraw): bool
    {
    }

    public function forceDelete(User $user, GoalWithdraw $goalWithdraw): bool
    {
    }
}
