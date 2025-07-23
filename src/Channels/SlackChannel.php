<?php
namespace MsTech\Notifier\Channels;

use Illuminate\Support\Facades\Http;
use MsTech\Notifier\Notifications\Notification;

class SlackChannel implements NotificationChannelInterface
{
    protected string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('notifier.slack_webhook_url') ?? env('SLACK_WEBHOOK_URL', '');
    }

    public function send($notifiable, Notification $notification): bool
    {
        if (empty($this->webhookUrl)) {
            return false; // No webhook URL configured
        }

        $payload = [
            'text' => $notification->getTitle() . "\n" . $notification->getMessage(),
        ];

        try {
            $response = Http::timeout(5)->post($this->webhookUrl, $payload);

            return $response->successful();
        } catch (\Exception $e) {
            // Optionally log the error
            // logger()->error('Slack notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
