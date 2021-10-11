<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScholarResponse;
use App\Models\ScholarshipRequirement;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarResponsePolicy
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
    public function viewAny(User $user, $requirement_id)
    {
        $requirement = ScholarshipRequirement::find($requirement_id);
        return (isset($requirement)? User::where('id', $user->id)->whereOfficerOf($requirement->scholarship_id)->exists(): false);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function view(User $user, ScholarResponse $scholarResponse)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can assess models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function assess(User $user, ScholarResponse $scholarResponse)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarResponse->requirement->scholarship_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function update(User $user, ScholarResponse $scholarResponse)
    {
        // 
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function delete(User $user, ScholarResponse $scholarResponse)
    {
        return $user->id == $scholarResponse->user_id && !$scholarResponse->cant_be_edit();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function restore(User $user, ScholarResponse $scholarResponse)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function forceDelete(User $user, ScholarResponse $scholarResponse)
    {
        //
    }
}
