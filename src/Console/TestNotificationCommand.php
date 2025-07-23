<?php
namespace MsTech\Notifier\Console;

use Illuminate\Console\Command;
use MsTech\Notifier\Notifications\Notification;
use MsTech\Notifier\Notifications\NotificationManager;

class TestNotificationCommand extends Command
{
    protected $signature = 'notifier:test {channel=email}';

    protected $description = 'Test sending notification via specified channel';

    protected NotificationManager $manager;

    public function __construct(NotificationManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    public function handle()
    {
        $channel = $this->argument('channel');

        $notifiable = (object)[
            'email' => 'test@example.com',
            'phone' => '123456789',
            // add other properties as needed
        ];

        $notification = new Notification('Test Notification', 'This is a test notification from your package.');

        $this->manager->send($notifiable, $notification, [$channel]);

        $this->info("Notification sent via channel: {$channel}");
    }
}
