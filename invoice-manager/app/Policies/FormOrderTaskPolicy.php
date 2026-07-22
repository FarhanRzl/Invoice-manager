<?php

namespace App\Policies;

use App\Models\FormOrderTask;
use App\Models\User;

class FormOrderTaskPolicy
{
    public function update(User $user, FormOrderTask $formOrderTask): bool
    {
        if ($formOrderTask->assigned_to === $user->id) {
            return true;
        }

        return $user->can('view', $formOrderTask->formOrder);
    }
}
