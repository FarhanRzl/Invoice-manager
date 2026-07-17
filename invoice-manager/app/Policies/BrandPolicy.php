<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Brand $brand): bool
    {
        return $user->hasRole('admin')
            || $user->brands()->whereKey($brand->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Brand $brand): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $user->hasRole('admin');
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Brand $brand): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Brand $brand): bool
    {
        return false;
    }
}
