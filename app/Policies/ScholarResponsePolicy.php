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
        return $user->id = $scholarResponse->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $requirement_id)
    {
        $scholarshipRequirement = ScholarshipRequirement::find('requirement_id', $requirement_id);

        return isset($scholarshipRequirement) && (
            (
                // returns true if scholar didn't have scholarship under this program and requirement are for applicants
                $scholarshipRequirement->promote 
                && $user->is_scholar()
                && !$user->is_scholar_of($scholarshipRequirement->scholarship_id)
            ) || (
                // returns true if scholar has the same category with requirement and requirement are for existing scholar's
                !$scholarshipRequirement->promote 
                && ScholarshipRequirementCategory::where('requirement_id', $scholarshipRequirement->id)
                    ->whereHas('category', function ($query) use ($user) {
                        $query->whereHas('scholars', function ($query) use ($user){
                            $query->where('user_id', $user->id);
                        });
                    })->exists()
            )
        );
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
     * Determine whether the user can submit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function submit(User $user, ScholarResponse $scholarResponse)
    {
        return !$scholarResponse->is_submitted() 
            && $user->id = $scholarResponse->user_id
            && $scholarResponse->requirement->enable !== false
            && (
                !$scholarResponse->is_late_to_submit()
                || $scholarResponse->requirement->enable === true
            );
    }

    /**
     * Determine whether the user can unsubmit the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarResponse  $scholarResponse
     * @return mixed
     */
    public function unsubmit(User $user, ScholarResponse $scholarResponse)
    {
        return $scholarResponse->is_submitted() 
            && $user->id = $scholarResponse->user_id
            && $scholarResponse->requirement->enable !== false
            && (
                !$scholarResponse->is_late_to_submit()
                || $scholarResponse->requirement->enable === true
            );
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
