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
    }
}
