<?php
namespace MsTech\Notifier\Notifications;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use MsTech\Notifier\Channels\NotificationChannelInterface;

class NotificationManager
{
    protected array $channels = [];
    protected ConfigRepository $config;

    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;

        // Load default channels from config
        $channelClasses = $this->config->get('notifier.channels', []);
        foreach ($channelClasses as $name => $class) {
            $this->registerChannel($name, app($class));
        }
    }

    /**
     * Register a notification channel.
     */
    public function registerChannel(string $name, NotificationChannelInterface $channel): void
    {
        $this->channels[$name] = $channel;
    }

    /**
     * Send notification via specified channels.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @param array $channels List of channel names; empty = all
     */
    public function send($notifiable, Notification $notification, array $channels = []): void
    {
        if (empty($channels)) {
            $channels = array_keys($this->channels);
        }

        foreach ($channels as $channelName) {
            if (isset($this->channels[$channelName])) {
                $this->channels[$channelName]->send($notifiable, $notification);
            }
        }
    }
}
