<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Interfaces\DispatcherInterface;
use App\Metatrader\Automation\Interfaces\EventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AbstractEventSubscriber implements DispatcherInterface
{
    /**
     * @Dependency
     */
    public EventDispatcherInterface $eventDispatcher;

    final public function dispatch(EventInterface $event): EventInterface
    {
        $this->eventDispatcher->dispatch($event, $event->getEventName());

        return $event;
    }
}
