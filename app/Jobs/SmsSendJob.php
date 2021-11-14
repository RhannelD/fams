<?php

namespace App\Jobs;

use App\Models\SmsSendTo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\SmsSendNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SmsSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $send_to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SmsSendTo  $send_to)
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
            'message'     => $this->send_to->sms_send->message,
        ];

        for ($attempt=0; $attempt < 3; $attempt++) { 
            try {
                $this->send_to->user->notify(new SmsSendNotification($details));
                $this->send_to->sent = true;
                break;
            } catch (\Exception $e) {
                $this->send_to->sent = false;
            }
        }

        $this->send_to->save();
    }
}
