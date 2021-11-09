<?php

namespace App\Jobs;

use App\Models\EmailSendTo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\ScholarshipSendMailNotification;

class ScholarshipSendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $send_to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(EmailSendTo $send_to)
    {
        $this->send_to = $send_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $details = [
            'scholarship' => $this->send_to->email_send->scholarship->scholarship,
            'sender'      => $this->send_to->email_send->user->flname(),
            'message'     => $this->send_to->email_send->message,
        ];

        for ($attempt=0; $attempt < 3; $attempt++) { 
            try {
                $this->send_to->user->notify(new ScholarshipSendMailNotification($details));
                $this->send_to->sent = true;
                break;
            } catch (\Exception $e) {
                $this->send_to->sent = false;
            }
        }

        $this->send_to->save();
    }
}
