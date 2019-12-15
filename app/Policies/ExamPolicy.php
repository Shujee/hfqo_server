<?php

namespace App\Policies;

use App\User;
use App\Exam;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any exams.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the exam.
     *
     * @param  \App\User  $user
     * @param  \App\Exam  $exam
     * @return mixed
     */
    public function view(User $user, Exam $exam)
    {
        return $user->isAdmin() || (!$user->trashed() && $exam->GetFirstValidAccess($user->id) != null);
    }

    /**
     * Determine whether the user can create exams.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the exam.
     *
     * @param  \App\User  $user
     * @param  \App\Exam  $exam
     * @return mixed
     */
    public function update(User $user, Exam $exam)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the exam.
     *
     * @param  \App\User  $user
     * @param  \App\Exam  $exam
     * @return mixed
     */
    public function delete(User $user, Exam $exam)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the exam.
     *
     * @param  \App\User  $user
     * @param  \App\Exam  $exam
     * @return mixed
     */
    public function restore(User $user, Exam $exam)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the exam.
     *
     * @param  \App\User  $user
     * @param  \App\Exam  $exam
     * @return mixed
     */
    public function forceDelete(User $user, Exam $exam)
    {
        return false;
    }
}