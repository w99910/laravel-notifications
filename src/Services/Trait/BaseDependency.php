<?php

namespace Thomasbrillion\Notification\Services\Trait;

use \Illuminate\Database\Query\Builder;

trait BaseDependency
{
    abstract protected function getNotificationQuery(): Builder;

    abstract protected function getUser(): \Illuminate\Foundation\Auth\User;
}
