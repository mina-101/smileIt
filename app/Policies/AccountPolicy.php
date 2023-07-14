<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AccountPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Account $account): bool
    {
        return Auth::user()->isAdmin() || $user->id == $account->user_id;
    }

    /**
     * Determine whether the user can view history of an account.
     */
    public function history(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id == $account->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }

}
