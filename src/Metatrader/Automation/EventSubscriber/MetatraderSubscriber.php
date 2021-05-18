<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Event\Metatrader\MetatraderExecutionEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @Subscriber
 */
class MetatraderSubscriber extends AbstractEventSubscriber
{
    /**
     * @Dependency
     */
    public ContainerBagInterface $containerBag;

    public function onMetatraderExecutionEvent(MetatraderExecutionEvent $event): void
    {
        if (!$this->isActiveExpertAdvisor($event))
        {
            $event->addError('The Expert Advisor ' . $event->getExpertAdvisor()->getName() . ' is not active');

            return;
        }

        if ($this->isFoundBacktestEntity($event))
        {
            $lastBacktestReport = $event->getBacktestEntity()->getLastBacktestReport();
        }

        foreach ($event->getExpertAdvisor()->getBacktestReportName($event->getBacktestEntity()) as $backtestReportName)
        {
            if (!isset($continue) && isset($lastBacktestReport) && $lastBacktestReport !== $backtestReportName)
            {
                continue;
            }

            $continue = true;

            if ($this->isFoundBacktestReportEntity($event, $backtestReportName))
            {
                echo $backtestReportName . ' already executed, skip...' . PHP_EOL;

                continue;
            }

            echo $backtestReportName . ' processing...' . PHP_EOL;

            // TODO: Prepare terminal.ini
            // TODO: Prepare expertAdvisor.ini
            // TODO: Tick data suite semaphore
            // TODO: Metatrader instance available
            // TODO: Metatrader backtest launch
            // TODO: Save backtest report to database

            $event->getBacktestEntity()->setLastBacktestReport($backtestReportName);

            if (!$this->isSavedEntity($event))
            {
                return;
            }
        }
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ParameterBag($this->containerBag->get('expert_advisors')[$name] ?? []);

        return new $class($name, $parameters);
    }

    private function isActiveExpertAdvisor(MetatraderExecutionEvent $event): bool
    {
        $event->setExpertAdvisor($this->getExpertAdvisorInstance($event->getBacktestEntity()->getExpertAdvisor()));

        return $event->getExpertAdvisor()->isActive();
    }

    private function isFoundBacktestEntity(MetatraderExecutionEvent $event): bool
    {
        $criteria        = ['name' => $event->getBacktestEntity()->getName()];
        $findEntityEvent = new FindEntityEvent(BacktestEntity::class, $criteria);
        $this->dispatch($findEntityEvent);

        if ($findEntityEvent->isFound())
        {
            /** @var BacktestEntity $backtestEntity */
            $backtestEntity = $findEntityEvent->getEntity();
            $event->setBacktestEntity($backtestEntity);

            return true;
        }

        return false;
    }

    private function isFoundBacktestReportEntity(MetatraderExecutionEvent $event, string $backtestReportName): bool
    {
        $criteria        = [
            'name'           => $backtestReportName,
            'expertAdvisor'  => $event->getExpertAdvisor()->getName(),
            'initialDeposit' => $event->getBacktestEntity()->getDeposit(),
            'period'         => $event->getBacktestEntity()->getPeriod(),
            'symbol'         => $event->getBacktestEntity()->getSymbol(),
        ];
        $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
        $this->dispatch($findEntityEvent);

        return $findEntityEvent->isFound();
    }

    private function isSavedEntity(MetatraderExecutionEvent $event): bool
    {
        $saveEntityEvent = new SaveEntityEvent($event->getBacktestEntity());
        $this->dispatch($saveEntityEvent);

        if ($saveEntityEvent->hasErrors())
        {
            foreach ($saveEntityEvent->getErrors() as $error)
            {
                $event->addError($error);
            }

            return false;
        }

        return true;
    }
}
