<?php
namespace MsTech\Notifier\Tests\Channels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\SentMessage;
use MsTech\Notifier\Channels\EmailChannel;
use MsTech\Notifier\Notifications\Notification;
use MsTech\Notifier\Tests\TestCase;

class EmailChannelTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        // Close Mockery if you add mocks in future tests
        \Mockery::close();
    }

    public function test_send_email_notification_success()
    {
        Mail::fake();

        $channel = new EmailChannel();

        $notifiable = (object)['email' => 'test@example.com'];

        $notification = new Notification('Hello', 'This is a test.');

        $result = $channel->send($notifiable, $notification);

        $this->assertTrue($result);

        Mail::assertSent(function (SentMessage $mail) use ($notifiable, $notification) {
            return $mail->hasTo($notifiable->email) &&
                $mail->subject === $notification->getTitle();
        });
    }

    public function test_send_email_notification_no_email_returns_false()
    {
        Mail::fake();

        $channel = new EmailChannel();

        $notifiable = (object)[];

        $notification = new Notification('Hello', 'Test message');

        $result = $channel->send($notifiable, $notification);

        $this->assertFalse($result);

        Mail::assertNothingSent();
    }
}
