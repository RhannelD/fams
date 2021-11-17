<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ScholarResponse;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ScholarResponseApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $response;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ScholarResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $evaluation = $this->response->approval? "approved": "denied";

        return (new MailMessage)
            ->subject('Requirement Evaluation')
            ->line("{$this->response->user->flname()} your requirement response has been {$evaluation}.")
            ->action('Open Response', route('requirement.view', [$this->response->requirement_id]));
    }

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
