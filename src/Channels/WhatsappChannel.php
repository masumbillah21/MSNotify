<?php
namespace MsTech\Notifier\Channels;

use Illuminate\Support\Facades\Http;
use MsTech\Notifier\Notifications\Notification;

class WhatsappChannel implements NotificationChannelInterface
{
    protected string $whatsappApiUrl;
    protected string $whatsappToken;
    protected string $whatsappPhoneNumberId;

    public function __construct()
    {
        // Load from config or env
        $this->whatsappApiUrl = config('notifier.whatsapp_api_url') ?? env('WHATSAPP_API_URL', 'https://graph.facebook.com/v15.0');
        $this->whatsappToken = config('notifier.whatsapp_token') ?? env('WHATSAPP_TOKEN');
        $this->whatsappPhoneNumberId = config('notifier.whatsapp_phone_number_id') ?? env('WHATSAPP_PHONE_NUMBER_ID');
    }

    public function send($notifiable, Notification $notification): bool
    {
        $phone = $notifiable->phone ?? null;
        if (!$phone) {
            return false;
        }

        if (empty($this->whatsappToken) || empty($this->whatsappPhoneNumberId)) {
            return false; // Missing credentials
        }

        $url = "{$this->whatsappApiUrl}/{$this->whatsappPhoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $phone,
            'type' => 'text',
            'text' => [
                'body' => $notification->getTitle() . "\n" . $notification->getMessage(),
            ],
        ];

        try {
            $response = Http::withToken($this->whatsappToken)
                ->post($url, $payload);

            return $response->successful();
        } catch (\Exception $e) {
            // Log error if needed
            // logger()->error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }
}
