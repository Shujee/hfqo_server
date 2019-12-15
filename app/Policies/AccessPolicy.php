<?php

namespace App\Policies;

use App\User;
use App\Access;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any accesses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the access.
     *
     * @param  \App\User  $user
     * @param  \App\Access  $access
     * @return mixed
     */
    public function view(User $user, Access $access)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create accesses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the access.
     *
     * @param  \App\User  $user
     * @param  \App\Access  $access
     * @return mixed
     */
    public function update(User $user, Access $access)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the access.
     *
     * @param  \App\User  $user
     * @param  \App\Access  $access
     * @return mixed
     */
    public function delete(User $user, Access $access)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the access.
     *
     * @param  \App\User  $user
     * @param  \App\Access  $access
     * @return mixed
     */
    public function restore(User $user, Access $access)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the access.
     *
     * @param  \App\User  $user
     * @param  \App\Access  $access
     * @return mixed
     */
    public function forceDelete(User $user, Access $access)
    {
        return false;
    }
}