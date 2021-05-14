<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

interface DispatcherInterface
{
    public function dispatch(EventInterface $event): EventInterface;
}
