<?php

namespace Thomasbrillion\Notification\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Thomasbrillion\Notification\Models\Notification;
use Thomasbrillion\Notification\Tests\Models\User;
use Thomasbrillion\Notification\NotificationServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        User::create(['id' => 1]);  // Create a default user for testing

        Notification::create([
            'id' => 1,
            'user_id' => 1,
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'status' => 'info',
            'priority' => 5,
        ]);
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

        $app['config']->set('notification', [
            'models' => [
                'notification' => \Thomasbrillion\Notification\Models\Notification::class,
            ],
            'middleware' => 'auth',
            'prefix' => 'notifications',
            'table_name' => 'test_notifications',
        ]);

        // Set up notification config
        // $app['config']->set('notification.models.notification', \Thomasbrillion\Notification\Models\Notification::class);
        // $app['config']->set('notification.middleware', 'auth');
    }
}
