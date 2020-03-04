<?php

namespace App\Policies;

use App\User;
use App\Download;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any downloads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the download.
     *
     * @param  \App\User  $user
     * @param  \App\Download  $download
     * @return mixed
     */
    public function view(User $user, Download $download)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create downloads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the download.
     *
     * @param  \App\User  $user
     * @param  \App\Download  $download
     * @return mixed
     */
    public function update(User $user, Download $download)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the download.
     *
     * @param  \App\User  $user
     * @param  \App\Download  $download
     * @return mixed
     */
    public function delete(User $user, Download $download)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the download.
     *
     * @param  \App\User  $user
     * @param  \App\Download  $download
     * @return mixed
     */
    public function restore(User $user, Download $download)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the download.
     *
     * @param  \App\User  $user
     * @param  \App\Download  $download
     * @return mixed
     */
    public function forceDelete(User $user, Download $download)
    {
        return false;
    }

         /**
     * Determine whether the user can create uploads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function uploadSnapshot(User $user, Download $download)
    {
        if($user->trashed())
            return false;
        else
            return $user->isAdmin() || $download->User->id == $user->id;
    }
}
