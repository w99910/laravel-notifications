<?php

namespace Thomasbrillion\Notification;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Thomasbrillion\Notification\Services\NotificationService;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any application services.
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'notification');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $destinationPath = $this->app->configPath('notification.php');

        if (file_exists($destinationPath)) {
            $this->mergeConfigFrom(__DIR__ . '/../config/notification.php', 'notification');
        } else {
            $this->publishes([
                __DIR__ . '/../config/notification.php' => $destinationPath,
            ], 'notification');
        }

        $this->app->bind(NotificationService::class, function ($app) {
            $user = $app->make('auth')->user();

            return new NotificationService($user);
        });
    }
}
