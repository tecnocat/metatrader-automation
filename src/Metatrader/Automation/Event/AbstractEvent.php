<?php

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Helper\ClassTools;

/**
 * Class AbstractEvent
 *
 * @package App\Metatrader\Automation\Event
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * @return string
     */
    public function getEventName(): string
    {
        return str_replace('.event', '', ClassTools::getClassNameDotted($this));
    }
}