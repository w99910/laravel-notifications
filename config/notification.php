<?php

return [
    'models' => [
        'notification' => Thomasbrillion\Notification\Models\Notification::class,
    ],
    'middleware' => 'auth',
    'prefix' => 'notifications',
];
