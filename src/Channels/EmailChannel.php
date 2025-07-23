<?php
namespace MsTech\Notifier\Channels;

use Illuminate\Support\Facades\Mail;
use MsTech\Notifier\Notifications\Notification;

class EmailChannel implements NotificationChannelInterface
{
    public function send($notifiable, Notification $notification): bool
    {
        $email = $notifiable->email ?? null;
        if (!$email) {
            return false;
        }

        Mail::raw($notification->getMessage(), function ($message) use ($email, $notification) {
            $message->to($email)
                    ->subject($notification->getTitle());
        });

        return true;
    }
}
