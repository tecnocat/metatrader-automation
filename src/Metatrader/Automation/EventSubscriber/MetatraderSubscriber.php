<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Event\MetatraderExecutionEvent;
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

    /**
     * TODO: Main workflow from other events only.
     */
    public function onMetatraderExecutionEvent(MetatraderExecutionEvent $metatraderExecutionEvent): void
    {
        $backtestEntity = $metatraderExecutionEvent->getBacktestEntity();
        $expertAdvisor  = $this->getExpertAdvisorInstance($backtestEntity->getExpertAdvisor());

        if (!$expertAdvisor->isActive())
        {
            $metatraderExecutionEvent->addError('The Expert Advisor ' . $expertAdvisor->getName() . ' is not active');

            return;
        }

        $criteria        = ['name' => $backtestEntity->getName()];
        $findEntityEvent = new FindEntityEvent(BacktestEntity::class, $criteria);
        $this->dispatch($findEntityEvent);

        if ($findEntityEvent->isFound())
        {
            /** @var BacktestEntity $backtestEntity */
            $backtestEntity     = $findEntityEvent->getEntity();
            $lastBacktestReport = $backtestEntity->getLastBacktestReport();
        }

        foreach ($expertAdvisor->getBacktestReportName($backtestEntity) as $backtestReportName)
        {
            if (!isset($continue) && isset($lastBacktestReport) && $lastBacktestReport !== $backtestReportName)
            {
                continue;
            }

            $continue        = true;
            $criteria        = [
                'name'           => $backtestReportName,
                'expertAdvisor'  => $expertAdvisor->getName(),
                'initialDeposit' => $backtestEntity->getDeposit(),
                'period'         => $backtestEntity->getPeriod(),
                'symbol'         => $backtestEntity->getSymbol(),
            ];
            $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                echo $backtestReportName . ' FOUND!' . PHP_EOL;

                continue;
            }

            // TODO: Prepare terminal.ini
            // TODO: Prepare expertAdvisor.ini
            // TODO: Tick data suite semaphore
            // TODO: Metatrader instance available
            // TODO: Metatrader backtest launch
            // TODO: Save backtest report to database

            $backtestEntity->setLastBacktestReport($backtestReportName);
            $saveEntityEvent = new SaveEntityEvent($backtestEntity);
            $this->dispatch($saveEntityEvent);

            if ($saveEntityEvent->hasErrors())
            {
                foreach ($saveEntityEvent->getErrors() as $error)
                {
                    $metatraderExecutionEvent->addError($error);
                }

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
}
