<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Helper\ClassTools;

abstract class AbstractEvent implements EventInterface
{
    public function getEventName(): string
    {
        return str_replace('.event', '', ClassTools::getClassNameDotted($this));
    }
}
