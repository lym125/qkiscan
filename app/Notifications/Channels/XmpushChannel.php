<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Qkiscan\Xmpush\Xmpush\Message;
use Qkiscan\Xmpush\Xmpush\Sender;
use RuntimeException;

class XmpushChannel
{
    /**
     * @var \Qkiscan\Xmpush\Xmpush\Sender
     */
    protected $sender;

    /**
     * @param \Qkiscan\Xmpush\Xmpush\Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
    }

    /**
      * Send the given notification.
      *
      * @param  mixed  $notifiable
      * @param  \Illuminate\Notifications\Notification  $notification
      * @return void
      */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->routeNotificationFor('xmpush', $notification);

        if (! $to) {
            return;
        }

        $message = $notification->toXmpush($notifiable);

        if (! $message instanceof Message) {
            return;
        }

        $message->build();

        $result = $this->sender->send($message, $to)->getRaw();

        if (data_get($result, 'code') !== 0) {
            throw new RuntimeException(
                data_get($result, 'description', '小米推送异常'),
                data_get($result, 'code', 0)
            );
        }
    }
}
