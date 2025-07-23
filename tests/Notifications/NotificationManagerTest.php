<?php
namespace MsTech\Notifier\Tests\Notifications;

use Mockery;
use MsTech\Notifier\Channels\NotificationChannelInterface;
use MsTech\Notifier\Notifications\Notification;
use MsTech\Notifier\Notifications\NotificationManager;
use MsTech\Notifier\Tests\TestCase;

class NotificationManagerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close(); // Verify all mocks
        parent::tearDown();
    }

    public function test_register_and_send_notification(): void
    {
        /** @var NotificationChannelInterface&\Mockery\MockInterface $mockChannel */
        $mockChannel = Mockery::mock(NotificationChannelInterface::class);

        $mockChannel->shouldReceive('send')
            ->once()
            ->withArgs(fn($notifiable, Notification $notification) =>
                $notification->getTitle() === 'Test' && $notifiable === 'user')
            ->andReturnTrue();

        $manager = $this->app->make(NotificationManager::class);

        $manager->registerChannel('mock', $mockChannel);

        $notification = new Notification('Test', 'Message');

        $manager->send('user', $notification, ['mock']);

        // Prevent risky test warning
        $this->assertTrue(true);
    }

    public function test_send_to_all_channels_if_none_specified(): void
    {
        /** @var NotificationChannelInterface&\Mockery\MockInterface $mockChannel1 */
        $mockChannel1 = Mockery::mock(NotificationChannelInterface::class);
        $mockChannel1->shouldReceive('send')->once()->andReturnTrue();

        /** @var NotificationChannelInterface&\Mockery\MockInterface $mockChannel2 */
        $mockChannel2 = Mockery::mock(NotificationChannelInterface::class);
        $mockChannel2->shouldReceive('send')->once()->andReturnTrue();

        $manager = $this->app->make(NotificationManager::class);

        $manager->registerChannel('one', $mockChannel1);
        $manager->registerChannel('two', $mockChannel2);

        $notification = new Notification('Test', 'Message');

        $manager->send('user', $notification);

        // Prevent risky test warning
        $this->assertTrue(true);
    }
}
