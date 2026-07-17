<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function view(User $user, Lead $lead): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $lead->brand_id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function update(User $user, Lead $lead): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $lead->brand_id)
            ->exists();
    }

    public function delete(User $user, Lead $lead): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $lead->brand_id)
            ->exists();
    }
}
