<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return ($user->isAdmin() || $user->id === $model->id);
    }

    /**
     * Determine whether the user can create new admin.
     */
    public function createAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id == $model->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }
}