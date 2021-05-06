<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

use App\Metatrader\Automation\Event\AbstractEvent;

interface DispatcherInterface
{
    public function dispatch(AbstractEvent $event): object;
}
