<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;

/**
 * @Subscriber
 */
class BacktestSubscriber extends AbstractEventSubscriber
{
    public function onExecutionEvent(ExecutionEvent $event): void
    {
        $criteria        = ['name' => $event->getBacktestDTO()->name];
        $findEntityEvent = new FindEntityEvent(BacktestEntity::class, $criteria);
        $this->dispatch($findEntityEvent);

        if ($findEntityEvent->isFound())
        {
            /** @var BacktestEntity $backtestEntity */
            $backtestEntity = $findEntityEvent->getEntity();
            $event->setBacktestEntity($backtestEntity);
        }
    }
}
