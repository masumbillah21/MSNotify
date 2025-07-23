<?php
namespace MsTech\Notifier\Providers;

use Illuminate\Support\ServiceProvider;
use MsTech\Notifier\Notifications\NotificationManager;

class NotifierServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge package config
        $this->mergeConfigFrom(__DIR__.'/../../config/notifier.php', 'notifier');

        // Bind NotificationManager singleton
        $this->app->singleton(NotificationManager::class, function ($app) {
            return new NotificationManager($app['config']);
        });
    }

    public function boot()
    {
        // Publish config for users to customize
        $this->publishes([
            __DIR__.'/../../config/notifier.php' => config_path('notifier.php'),
        ], 'config');
    }
}
