<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

interface EventInterface
{
    public function getEventName(): string;
}
