<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | List all available notification channels and their classes here.
    | Users can add or override channels by publishing this config.
    |
    */
    'channels' => [
        'email' => \MsTech\Notifier\Channels\EmailChannel::class,
        'sms' => \MsTech\Notifier\Channels\SmsChannel::class,
        'slack' => \MsTech\Notifier\Channels\SlackChannel::class,
        'whatsapp' => \MsTech\Notifier\Channels\WhatsappChannel::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Configuration
    |--------------------------------------------------------------------------
    |
    | Additional email settings can go here if needed.
    | Email sending typically uses Laravel's default mail config.
    |
    */

    // 'email' => [
    //     // any email-specific settings
    // ],

    /*
    |--------------------------------------------------------------------------
    | Twilio SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Your Twilio credentials and phone number for SMS sending.
    | These are used by SmsChannel.
    |
    */
    'twilio_sid' => env('TWILIO_SID', ''),
    'twilio_token' => env('TWILIO_TOKEN', ''),
    'twilio_from' => env('TWILIO_FROM', ''),

    /*
    |--------------------------------------------------------------------------
    | Slack Configuration
    |--------------------------------------------------------------------------
    |
    | Slack Incoming Webhook URL.
    |
    */
    'slack_webhook_url' => env('SLACK_WEBHOOK_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for WhatsApp Business Cloud API integration.
    |
    */
    'whatsapp_api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v15.0'),
    'whatsapp_token' => env('WHATSAPP_TOKEN', ''),
    'whatsapp_phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', ''),

];
