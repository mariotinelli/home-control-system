<?php

namespace App\Policies;

use App\Models\{TripEntry, User};

class TripEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('trip_entry_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TripEntry $tripEntry): bool
    {
        return $user->hasPermissionTo('trip_entry_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('trip_entry_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TripEntry $tripEntry): bool
    {
        return $user->hasPermissionTo('trip_entry_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TripEntry $tripEntry): bool
    {
        return $user->hasPermissionTo('trip_entry_delete');
    }

}
