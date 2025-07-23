<?php
namespace MsTech\Notifier\Channels;

use MsTech\Notifier\Notifications\Notification;

interface NotificationChannelInterface
{
    /**
     * Send notification to notifiable entity.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return bool
     */
    public function send($notifiable, Notification $notification): bool;
}
