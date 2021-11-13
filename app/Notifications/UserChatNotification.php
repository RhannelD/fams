<?php

namespace App\Notifications;

use App\Models\UserChat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserChatNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user_chat;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserChat $user_chat)
    {
        $this->user_chat = $user_chat;
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
        return (new MailMessage)
            ->subject('Chat')
            ->line("{$this->user_chat->sender->flname()} sent you a message.")
            ->action('Open Chat', route('user.chat', ['rid' => $this->user_chat->sender_id]));
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
