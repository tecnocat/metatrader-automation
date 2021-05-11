<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

use App\Metatrader\Automation\Event\EventInterface;

interface DispatcherInterface
{
    public function dispatch(EventInterface $event): object;
}
