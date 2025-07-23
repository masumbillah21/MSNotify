<?php
namespace MsTech\Notifier\Channels;

use Illuminate\Support\Facades\Http;
use MsTech\Notifier\Notifications\Notification;

class SmsChannel implements NotificationChannelInterface
{
    protected string $twilioSid;
    protected string $twilioToken;
    protected string $twilioFrom;

    public function __construct()
    {
        // Load Twilio credentials from config or .env
        $this->twilioSid = config('notifier.twilio_sid') ?? env('TWILIO_SID');
        $this->twilioToken = config('notifier.twilio_token') ?? env('TWILIO_TOKEN');
        $this->twilioFrom = config('notifier.twilio_from') ?? env('TWILIO_FROM');
    }

    public function send($notifiable, Notification $notification): bool
    {
        $phone = $notifiable->phone ?? null;
        if (!$phone) {
            return false;
        }

        if (empty($this->twilioSid) || empty($this->twilioToken) || empty($this->twilioFrom)) {
            return false; // Missing credentials
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->twilioSid}/Messages.json";

        try {
            $response = Http::withBasicAuth($this->twilioSid, $this->twilioToken)
                ->asForm()
                ->post($url, [
                    'From' => $this->twilioFrom,
                    'To' => $phone,
                    'Body' => $notification->getTitle() . "\n" . $notification->getMessage(),
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            // Log the error if needed
            // logger()->error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
