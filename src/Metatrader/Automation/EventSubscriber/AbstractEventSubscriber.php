<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Event\EventInterface;
use App\Metatrader\Automation\Interfaces\DispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AbstractEventSubscriber implements DispatcherInterface
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    final public function dispatch(EventInterface $event): object
    {
        return $this->eventDispatcher->dispatch($event, $event->getEventName());
    }
}
