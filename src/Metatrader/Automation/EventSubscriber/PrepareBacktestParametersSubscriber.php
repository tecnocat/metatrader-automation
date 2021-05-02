<?php

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\PrepareBacktestParametersCommandEvent;
use App\Metatrader\Automation\Event\PrepareBacktestParametersRequestEvent;

/**
 * Class PrepareBacktestParametersSubscriber
 *
 * @Subscriber
 *
 * @package App\Metatrader\Automation\EventSubscriber
 */
class PrepareBacktestParametersSubscriber
{
    /**
     * @param PrepareBacktestParametersCommandEvent $event
     */
    public function onCommandEvent(PrepareBacktestParametersCommandEvent $event): void
    {
        $event->setParameters(array_merge($event->getInput()->getArguments(), $event->getInput()->getOptions()));
    }

    /**
     * @param PrepareBacktestParametersRequestEvent $event
     */
    public function onRequestEvent(PrepareBacktestParametersRequestEvent $event): void
    {
        $event->setParameters($event->getRequest()->request->all());
    }
}