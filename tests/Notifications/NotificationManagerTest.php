<?php
namespace MsTech\Notifier\Tests\Notifications;

use Mockery;
use MsTech\Notifier\Channels\NotificationChannelInterface;
use MsTech\Notifier\Notifications\Notification;
use MsTech\Notifier\Notifications\NotificationManager;
use MsTech\Notifier\Tests\TestCase;

class NotificationManagerTest extends TestCase
{
    public function tearDown(): void
    {
        // Verify all expectations on mocks were met
        Mockery::close();
        parent::tearDown();
    }

    public function test_register_and_send_notification()
    {
        /** @var NotificationChannelInterface $mockChannel */
        $mockChannel = Mockery::mock(NotificationChannelInterface::class);

        $mockChannel->shouldReceive('send')
            ->once()
            ->withArgs(function ($notifiable, Notification $notification) {
                return $notification->getTitle() === 'Test' && $notifiable === 'user';
            })
            ->andReturn(true);

        $manager = $this->app->make(NotificationManager::class);

        $manager->registerChannel('mock', $mockChannel);

        $notification = new Notification('Test', 'Message');

        $manager->send('user', $notification, ['mock']);

        // Assert that expectations were met (optional since Mockery::close() will check)
        $this->assertTrue(true);
    }

    public function test_send_to_all_channels_if_none_specified()
    {
        /** @var NotificationChannelInterface $mockChannel1 */
        $mockChannel1 = Mockery::mock(NotificationChannelInterface::class);
        $mockChannel1->shouldReceive('send')->once()->andReturnTrue();

        /** @var NotificationChannelInterface $mockChannel2 */
        $mockChannel2 = Mockery::mock(NotificationChannelInterface::class);
        $mockChannel2->shouldReceive('send')->once()->andReturnTrue();

        $manager = $this->app->make(NotificationManager::class);

        $manager->registerChannel('one', $mockChannel1);
        $manager->registerChannel('two', $mockChannel2);

        $notification = new Notification('Test', 'Message');

        $manager->send('user', $notification);

        // Dummy assertion to avoid risky test
        $this->assertTrue(true);
    }
}
