<?php

namespace App\Policies;

use App\Models\ScholarshipRequirement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipRequirementPolicy
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
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function view(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarshipRequirement->scholarship_id)->exists();
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
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function update(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarshipRequirement->scholarship_id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function delete(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarshipRequirement->scholarship_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function restore(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        //
    }
}
