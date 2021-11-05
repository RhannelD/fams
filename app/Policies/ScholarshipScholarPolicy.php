<?php

namespace App\Policies;

use App\Models\ScholarshipScholar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipScholarPolicy
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
        return $user->is_officer() 
            || User::where('id', $user->id)
                ->whereScholarOf($scholarship_id)
                ->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipScholar  $scholarshipScholar
     * @return mixed
     */
    public function view(User $user, ScholarshipScholar $scholarshipScholar)
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
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipScholar  $scholarshipScholar
     * @return mixed
     */
    public function update(User $user, ScholarshipScholar $scholarshipScholar)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipScholar  $scholarshipScholar
     * @return mixed
     */
    public function delete(User $user, ScholarshipScholar $scholarshipScholar)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipScholar  $scholarshipScholar
     * @return mixed
     */
    public function restore(User $user, ScholarshipScholar $scholarshipScholar)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipScholar  $scholarshipScholar
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipScholar $scholarshipScholar)
    {
        //
    }
}
