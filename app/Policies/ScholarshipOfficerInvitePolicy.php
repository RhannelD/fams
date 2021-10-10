<?php

namespace App\Policies;

use App\Models\ScholarshipOfficerInvite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipOfficerInvitePolicy
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
    public function viewAny(User $user, $scholarship_id)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship_id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function viewUserInvite(User $user)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function view(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        return $user->id == $scholarshipOfficer->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $scholarship_id)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function update(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        return $user->email == $scholarshipOfficerInvite->email;
    }

    /**
     * Determine whether the user can resend the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function resend(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarshipOfficerInvite->scholarship_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updateMany(User $user, $scholarship_id)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function delete(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarshipOfficerInvite->scholarship_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function deleteMany(User $user, $scholarship_id)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function restore(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipOfficerInvite  $scholarshipOfficerInvite
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipOfficerInvite $scholarshipOfficerInvite)
    {
        //
    }
}
