<?php

namespace App\Observers;

use App\Models\ScholarResponseComment;

use App\Models\User;
use App\Notifications\ScholarResponseCommentNotification;

class ScholarResponseCommentObserver
{
    /**
     * Handle the ScholarResponseComment "created" event.
     *
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return void
     */
    public function created(ScholarResponseComment $scholarResponseComment)
    {
        $response_id =  $scholarResponseComment->response_id;
        $user_id =      $scholarResponseComment->user_id;
        $users = User::whereHas('response_comments', function ($query) use ($response_id, $user_id) {
                $query->where('response_id', $response_id)
                    ->where('user_id', '!=', $user_id);
            })
            ->get();

        foreach ($users as $user) {
            $details = [
                'commenter' => $scholarResponseComment->user->flname(),
                'comment' => $scholarResponseComment->comment,
            ];
    
            if ( $user->is_scholar() ) {
                $details['body_message'] = "{$scholarResponseComment->user->flname()} commented on your requirement response.";
                $details['url'] = route('requirement.view', [$scholarResponseComment->response->requirement_id]);
            } else {
                $details['body_message'] = "{$scholarResponseComment->user->flname()} commented on a requirement response of {$scholarResponseComment->response->user->flname()}.";
                $details['url'] = route('scholarship.requirement.responses', [
                        'requirement_id' => $scholarResponseComment->response->requirement_id,
                        'search' => $scholarResponseComment->response->user->email,
                        'index' => 0,
                    ]);
            }

            if ( isset($details['body_message']) ) {
                try {
                    $user->notify(new ScholarResponseCommentNotification($details));
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
    }

    /**
     * Handle the ScholarResponseComment "updated" event.
     *
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return void
     */
    public function updated(ScholarResponseComment $scholarResponseComment)
    {
        //
    }

    /**
     * Handle the ScholarResponseComment "deleted" event.
     *
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return void
     */
    public function deleted(ScholarResponseComment $scholarResponseComment)
    {
        //
    }

    /**
     * Handle the ScholarResponseComment "restored" event.
     *
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return void
     */
    public function restored(ScholarResponseComment $scholarResponseComment)
    {
        //
    }

    /**
     * Handle the ScholarResponseComment "force deleted" event.
     *
     * @param  \App\Models\ScholarResponseComment  $scholarResponseComment
     * @return void
     */
    public function forceDeleted(ScholarResponseComment $scholarResponseComment)
    {
        //
    }
}
