<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\PrepareBacktestParametersCommandEvent;
use App\Metatrader\Automation\Event\PrepareBacktestParametersRequestEvent;

/**
 * @Subscriber
 */
class PrepareBacktestParametersSubscriber
{
    public function onCommandEvent(PrepareBacktestParametersCommandEvent $event): void
    {
        $event->setParameters(array_merge($event->getInput()->getArguments(), $event->getInput()->getOptions()));
    }

    public function onRequestEvent(PrepareBacktestParametersRequestEvent $event): void
    {
        $event->setParameters($event->getRequest()->request->all());
    }
}
