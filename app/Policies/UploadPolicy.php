<?php

namespace App\Policies;

use App\User;
use App\Upload;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class UploadPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any uploads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function view(User $user, Upload $upload)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create uploads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if($user->trashed())
            return false;
        
        //This action requires that user has been granted access to the specified exam        
        if(!request()->filled('exam_id'))
            return false;

        $Exam = \App\Exam::find(request()['exam_id']);

        if($Exam == null || $Exam->trashed() || $Exam->is_expired)
            return false;
        else
            return $Exam->ValidAccessExists($user->id);
    }

    /**
     * Determine whether the user can update the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function update(User $user, Upload $upload)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function delete(User $user, Upload $upload)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function restore(User $user, Upload $upload)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the upload.
     *
     * @param  \App\User  $user
     * @param  \App\Upload  $upload
     * @return mixed
     */
    public function forceDelete(User $user, Upload $upload)
    {
        return false;
    }
}
