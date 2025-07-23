<?php
namespace MsTech\Notifier\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \MsTech\Notifier\Providers\NotifierServiceProvider::class,
        ];
    }
}
