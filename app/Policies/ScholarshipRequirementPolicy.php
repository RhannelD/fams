<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScholarResponse;
use App\Models\ScholarshipRequirement;
use App\Models\ScholarshipRequirementCategory;
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
     * Determine whether the user can preview the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function preview(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return (
                $scholarshipRequirement->promote 
                && $user->is_scholar()
            ) || (
                User::where('id', $user->id)->whereScholarOf($scholarshipRequirement->scholarship_id)->exists()
            );
    }

    /**
     * Determine whether the user can access the model if under the same category
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function access_under_category(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return ScholarshipRequirementCategory::where('requirement_id', $scholarshipRequirement->id)
            ->whereHas('category', function ($query) use ($user) {
                $query->whereHas('scholars', function ($query) use ($user){
                    $query->where('user_id', $user->id);
                });
            })
            ->exists();
    }

    /**
     * Determine whether the user can access the model if scholar on this scholarship
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function access_as_scholar(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return $user->is_scholar_of($scholarshipRequirement->scholarship_id);
    }

    /**
     * Determine whether the user can respond to models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirement  $scholarshipRequirement
     * @return mixed
     */
    public function respond(User $user, ScholarshipRequirement $scholarshipRequirement)
    {
        return (
            // returns true if scholar didn't have scholarship under this program and requirement are for applicants
            $scholarshipRequirement->promote 
            && $user->is_scholar()
            && !$user->is_scholar_of($scholarshipRequirement->scholarship_id)
            && $scholarshipRequirement->has_categories()
        ) || (
            // returns true if scholar has the same category with requirement and requirement are for existing scholar's
            !$scholarshipRequirement->promote 
            && ScholarshipRequirementCategory::where('requirement_id', $scholarshipRequirement->id)
                ->whereHas('category', function ($query) use ($user) {
                    $query->whereHas('scholars', function ($query) use ($user){
                        $query->where('user_id', $user->id);
                    });
                })->exists()
        ) || (
            // returns true if scholar has existing response
            ScholarResponse::where('user_id', $user->id)
                ->where('requirement_id', $scholarshipRequirement->id)
                ->exists()
        );
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
