<?php

namespace Thomasbrillion\Notification;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register any application services.
    }

    public function boot()
    {
        Broadcast::channel('users.{id}', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
