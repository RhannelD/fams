<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScholarshipOfficer;
use App\Models\ScholarshipCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipCategoryPolicy
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
        return $user->is_officer() || User::where('id', $user->id)->whereScholarOf($scholarship_id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipCategory  $scholarshipCategory
     * @return mixed
     */
    public function view(User $user, ScholarshipCategory $scholarshipCategory)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $scholarship_id)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipCategory  $scholarshipCategory
     * @return mixed
     */
    public function update(User $user, ScholarshipCategory $scholarshipCategory)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipCategory  $scholarshipCategory
     * @return mixed
     */
    public function delete(User $user, ScholarshipCategory $scholarshipCategory)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipCategory  $scholarshipCategory
     * @return mixed
     */
    public function restore(User $user, ScholarshipCategory $scholarshipCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipCategory  $scholarshipCategory
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipCategory $scholarshipCategory)
    {
        //
    }
}
