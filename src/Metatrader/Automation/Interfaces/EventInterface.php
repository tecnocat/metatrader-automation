<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

interface EventInterface
{
    public function getEventName(): string;
}
