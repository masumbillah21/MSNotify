<?php
namespace MsTech\Notifier\Tests\Channels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\SentMessage;
use Symfony\Component\Mime\Email;
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

    public function test_send_email_notification_success(): void
    {
        Mail::fake();

        $channel = new EmailChannel();

        /** @var object $notifiable */
        $notifiable = (object)['email' => 'test@example.com'];

        $notification = new Notification('Hello', 'This is a test.');

        $result = $channel->send($notifiable, $notification);

        $this->assertTrue($result);

        Mail::assertSent(function (SentMessage $mail) use ($notifiable, $notification) {
            $message = $mail->getOriginalMessage();

            if ($message instanceof Email) {
                return $mail->hasTo($notifiable->email) &&
                    $message->getSubject() === $notification->getTitle();
            }

            return $mail->hasTo($notifiable->email);
        });

    }

    public function test_send_email_notification_no_email_returns_false(): void
    {
        Mail::fake();

        $channel = new EmailChannel();

        /** @var object $notifiable */
        $notifiable = (object)[];

        $notification = new Notification('Hello', 'Test message');

        $result = $channel->send($notifiable, $notification);

        $this->assertFalse($result);

        Mail::assertNothingSent();
    }
}
