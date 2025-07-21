<?php

return [
    'models' => [
        'notification' => Thomasbrillion\Notification\Models\Notification::class,
    ],
    'middleware' => 'auth',
    'prefix' => 'notifications',
    /** Change the table name if you want to use a different one for default notification model. */
    'table_name' => 'notifications',
];
