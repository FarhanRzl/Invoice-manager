<?php

namespace App\Policies;

use App\Models\FormOrder;
use App\Models\User;

class FormOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function view(User $user, FormOrder $formOrder): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $formOrder->brand_id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function update(User $user, FormOrder $formOrder): bool
    {
        if ($formOrder->is_locked) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $formOrder->brand_id)
            ->exists();
    }

    public function delete(User $user, FormOrder $formOrder): bool
    {
        if ($formOrder->is_locked) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $formOrder->brand_id)
            ->exists();
    }
}
