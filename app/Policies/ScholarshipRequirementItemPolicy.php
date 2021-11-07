<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScholarResponseFile;
use App\Models\ScholarResponseAnswer;
use App\Models\ScholarResponseOption;
use App\Models\ScholarshipRequirementItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipRequirementItemPolicy
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
        if ($user->is_admin() && $ability != 'updateType' ) {
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
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function view(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
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
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function update(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can update type of the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function updateType(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
    {
        return ($user->is_officer() || $user->is_admin())
            && !ScholarResponseAnswer::where('item_id', $scholarshipRequirementItem->id)->exists()
            && !ScholarResponseFile::where('item_id', $scholarshipRequirementItem->id)->exists()
            && !ScholarResponseOption::where('item_id', $scholarshipRequirementItem->id)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function delete(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
    {
        return $user->is_officer();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function restore(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipRequirementItem  $scholarshipRequirementItem
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipRequirementItem $scholarshipRequirementItem)
    {
        //
    }
}
