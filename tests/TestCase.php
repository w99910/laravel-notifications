<?php

namespace Thomasbrillion\Notification\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thomasbrillion\Notification\NotificationServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            NotificationServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up broadcasting config
        $app['config']->set('broadcasting.default', 'null');
        $app['config']->set('broadcasting.connections.null', [
            'driver' => 'null',
        ]);

        // Set up event config
        $app['config']->set('app.env', 'testing');

        // Set up notification config
        // $app['config']->set('notification.models.notification', \Thomasbrillion\Notification\Models\Notification::class);
        // $app['config']->set('notification.middleware', 'auth');
    }
}
