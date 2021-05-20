<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Subscriber
 */
class AnnotationsCompilerPassTestMethodCallSubscriber
{
    /**
     * @Dependency
     */
    private EventDispatcherInterface $eventDispatcher;

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onAnnotationsCompilerPassTestEvent(AnnotationsCompilerPassTestEvent $event): void
    {
        $event->addError('error: ' . $event->getTest());
    }
}
