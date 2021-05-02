<?php

namespace App\Metatrader\Automation\Event;

/**
 * Interface EventInterface
 *
 * @package App\Metatrader\Automation\Event
 */
interface EventInterface
{
    /**
     * @return string
     */
    public function getEventName(): string;
}