<?php

namespace App\Observers;

use App\Models\ScholarshipPost;

use App\Models\User;
use App\Notifications\ScholarshipPostNotification;

class ScholarshipPostObserver
{
    /**
     * Handle the ScholarshipPost "created" event.
     *
     * @param  \App\Models\ScholarshipPost  $scholarshipPost
     * @return void
     */
    public function created(ScholarshipPost $scholarshipPost)
    {
        $users = User::whereScholarOf($scholarshipPost->scholarship_id)->get();

        $details = [
            'scholarship' => $scholarshipPost->scholarship->scholarship,
            'url' => route('post.show', [$scholarshipPost->id]),
            'poster' => $scholarshipPost->user->flname(),
        ];

        foreach ($users as $user) {
            try {
                $user->notify(new ScholarshipPostNotification($details));
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    /**
     * Handle the ScholarshipPost "updated" event.
     *
     * @param  \App\Models\ScholarshipPost  $scholarshipPost
     * @return void
     */
    public function updated(ScholarshipPost $scholarshipPost)
    {
        //
    }

    /**
     * Handle the ScholarshipPost "deleted" event.
     *
     * @param  \App\Models\ScholarshipPost  $scholarshipPost
     * @return void
     */
    public function deleted(ScholarshipPost $scholarshipPost)
    {
        //
    }

    /**
     * Handle the ScholarshipPost "restored" event.
     *
     * @param  \App\Models\ScholarshipPost  $scholarshipPost
     * @return void
     */
    public function restored(ScholarshipPost $scholarshipPost)
    {
        //
    }

    /**
     * Handle the ScholarshipPost "force deleted" event.
     *
     * @param  \App\Models\ScholarshipPost  $scholarshipPost
     * @return void
     */
    public function forceDeleted(ScholarshipPost $scholarshipPost)
    {
        //
    }
}
