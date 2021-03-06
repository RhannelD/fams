<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Scholarship;
use App\Traits\YearSemTrait;
use App\Models\ScholarshipPost;
use App\Models\ScholarshipPostComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipPostCommentPolicy
{
    use HandlesAuthorization;
    use YearSemTrait;

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
     * @param  \App\Models\ScholarshipPostComment  $scholarshipPostComment
     * @return mixed
     */
    public function view(User $user, ScholarshipPostComment $scholarshipPostComment)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, $post_id)
    {
        $scholarshipPost =  ScholarshipPost::find($post_id);
        if ( is_null($scholarshipPost) ) 
            return false;
        
        if ( $scholarshipPost->promote ) 
            return true;

        $acad_year = $this->get_acad_year();
        $acad_sem  = $this->get_acad_sem();
        
        return $user->is_officer() || Scholarship::where('id', $scholarshipPost->scholarship_id)
            ->where(function ($query) use ($user, $acad_year, $acad_sem) {
                $query->whereHas('categories', function ($query) use ($user, $acad_year, $acad_sem) {
                    $query->whereHas('scholars', function ($query) use ($user, $acad_year, $acad_sem) {
                        $query->where('user_id', $user->id)
                            ->where('acad_year', $acad_year)
                            ->where('acad_sem', $acad_sem);
                    });
                });
            })
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipPostComment  $scholarshipPostComment
     * @return mixed
     */
    public function update(User $user, ScholarshipPostComment $scholarshipPostComment)
    {
        return $user->id == $scholarshipPostComment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipPostComment  $scholarshipPostComment
     * @return mixed
     */
    public function delete(User $user, ScholarshipPostComment $scholarshipPostComment)
    {
        return $user->id == $scholarshipPostComment->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipPostComment  $scholarshipPostComment
     * @return mixed
     */
    public function restore(User $user, ScholarshipPostComment $scholarshipPostComment)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ScholarshipPostComment  $scholarshipPostComment
     * @return mixed
     */
    public function forceDelete(User $user, ScholarshipPostComment $scholarshipPostComment)
    {
        //
    }
}
