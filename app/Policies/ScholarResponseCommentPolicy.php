<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScholarResponse;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarResponseComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarResponseCommentPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->is_admin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return mixed
     */
    public function view(User $user, ScholarResponseComment $scholarResponseComment)
    {
        return $user->id == $scholarResponseComment->user_id || $user->is_officer();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $response_id)
    {
        return $user->is_officer() 
            || ScholarResponse::where('id', $response_id)
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return mixed
     */
    public function update(User $user, ScholarResponseComment $scholarResponseComment)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return mixed
     */
    public function delete(User $user, ScholarResponseComment $scholarResponseComment)
    {
        return $user->id == $scholarResponseComment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return mixed
     */
    public function restore(User $user, ScholarResponseComment $scholarResponseComment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return mixed
     */
    public function forceDelete(User $user, ScholarResponseComment $scholarResponseComment)
    {
        //
    }
}
