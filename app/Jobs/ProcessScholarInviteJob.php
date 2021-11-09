<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\ScholarInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Models\ScholarshipScholarInvite;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessScholarInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invitation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ScholarshipScholarInvite $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $details = [
            'scholarship' => $this->invitation->category->scholarship->scholarship
        ];

        try {
            Mail::to($this->invitation->email)->send(new ScholarInvitationMail($details));
            $this->invitation->sent = true;
        } catch (\Exception $e) {
            $this->invitation->sent = false;
        }
        $this->invitation->save();
    }
}
