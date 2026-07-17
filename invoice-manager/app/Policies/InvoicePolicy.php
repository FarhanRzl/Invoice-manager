<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $invoice->brand_id)
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin')
            || $user->hasRole('brand_user');
    }

    public function viewReceipt(User $user, Invoice $invoice): bool
    {
        return $invoice->status === 'lunas'
            && $this->view($user, $invoice);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        if ($invoice->status !== 'menunggu') {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $invoice->brand_id)
            ->exists();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        if ($invoice->status !== 'menunggu') {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        if (! $user->hasRole('brand_user')) {
            return false;
        }

        return $user->brands()
            ->where('brands.id', $invoice->brand_id)
            ->exists();
    }
}