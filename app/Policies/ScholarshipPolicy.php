<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipOfficer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipPolicy
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
        return $user->is_officer();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function view(User $user, Scholarship $scholarship)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship->id)->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function viewDashboard(User $user, Scholarship $scholarship)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship->id)->exists();
    }

    /**
     * Determine whether the user can send emails from the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function sendEmails(User $user, Scholarship $scholarship)
    {
        return User::where('id', $user->id)->whereOfficerOf($scholarship->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_admin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function update(User $user, Scholarship $scholarship)
    {
        return ScholarshipOfficer::where('user_id', $user->id)->where('scholarship_id', $scholarship->id)->whereAdmin()->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function delete(User $user, Scholarship $scholarship)
    {
        return $user->is_admin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function restore(User $user, Scholarship $scholarship)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Scholarship  $scholarship
     * @return mixed
     */
    public function forceDelete(User $user, Scholarship $scholarship)
    {
        //
    }
}
