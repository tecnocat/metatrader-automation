<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Metatrader\FindTerminalEvent;
use App\Metatrader\Automation\Helper\TerminalHelper;

/**
 * @Subscriber
 */
class TerminalSubscriber extends AbstractEventSubscriber
{
    public function onFindEvent(FindTerminalEvent $event): void
    {
        $event->setTerminalDTO(TerminalHelper::findOneFree($event->getDataPath()));
    }
}
