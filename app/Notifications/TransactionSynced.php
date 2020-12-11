<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Qkiscan\Xmpush\Xmpush\Message;

class TransactionSynced extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Qkiscan\Xmpush\Xmpush\Message
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param \Qkiscan\Xmpush\Xmpush\Message $message
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [Channels\XmpushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toXmpush($notifiable)
    {
        return $this->message;
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
