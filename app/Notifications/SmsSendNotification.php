<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Aldemeery\BulkSMS\Messages\BulkSMSMessage;
use Humans\Semaphore\Laravel\SemaphoreChannel;
use Humans\Semaphore\Laravel\SemaphoreMessage;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Messages\NexmoMessage;

class SmsSendNotification extends Notification
{
    use Queueable;
    public $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = (object) $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack', SemaphoreChannel::class];
        // return ['bulkSms'];
        // return ['nexmo'];
    }

    public function toSemaphore($notifiable)
    {
        return (new SemaphoreMessage)
            ->message($this->details->message);
    }

    // /**
    //  * Get the Vonage / SMS representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return \Illuminate\Notifications\Messages\NexmoMessage
    //  */
    // public function toNexmo($notifiable)
    // {
    //     return (new NexmoMessage)
    //         ->content($this->details->message);
    // }

    // /**
    //  * Get the BulkSMS representation of the notification.
    //  *
    //  * @param mixed $notifiable Notifiable instance.
    //  *
    //  * @return \Aldemeery\BulkSMS\Messages\BulkSMSMessage
    //  */
    // public function toBulkSms($notifiable)
    // {
    //     return new BulkSMSMessage($this->details->message);
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
