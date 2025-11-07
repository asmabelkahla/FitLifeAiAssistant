<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user is an admin.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function accessDashboard(User $user)
    {
        return $user->role === 'admin';
    }
}
