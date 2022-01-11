<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;
use App\Metatrader\Automation\Event\Metatrader\FindTerminalEvent;
use App\Metatrader\Automation\Event\Metatrader\WriteConfigEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @Subscriber
 */
class WorkflowSubscriber extends AbstractEventSubscriber
{
    /**
     * @Dependency
     */
    public ContainerBagInterface $containerBag;

    public function onExecutionEvent(ExecutionEvent $event): void
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

        foreach ($event->getExpertAdvisor()->generateBacktestReportName($event->getBacktestEntity()) as $backtestReportName)
        {
            if (!isset($continue) && isset($lastBacktestReport) && $lastBacktestReport !== $backtestReportName)
            {
                continue;
            }

            $continue = true;

            if ($this->isFoundBacktestReportEntity($event, $backtestReportName))
            {
                // TODO: Remove this direct output or change to output event
                echo $backtestReportName . ' already executed, skip...' . PHP_EOL;

                continue;
            }

            // TODO: Remove this direct output or change to output event
            echo $backtestReportName . ' processing...' . PHP_EOL;

            if (!$this->findTerminal($event))
            {
                $event->addError('Unable to find free Terminal');

                return;
            }

            if (!$this->writeConfig($event, WriteConfigEvent::TESTER_CONFIG_TYPE))
            {
                $event->addError('Unable to build the Terminal config');

                return;
            }

            if (!$this->writeConfig($event, WriteConfigEvent::EXPERT_ADVISOR_CONFIG_TYPE))
            {
                $event->addError('Unable to build the Expert Advisor config');

                return;
            }

            $terminalDTO = $event->getTerminalDTO();

            // TODO: Tick data suite semaphore
            // TODO: Metatrader backtest launch

            // TODO: Sync mode
            exec(sprintf('"%s" "%s"', $terminalDTO->terminalExe, $terminalDTO->terminalConfig));

            // TODO: Async mode
            //pclose(popen($debug = sprintf('start /b %s %s', $terminalDTO->terminalExe, $terminalDTO->terminalConfig), 'r'));

            // TODO: Save backtest report to database

            $event->getBacktestEntity()->setLastBacktestReport($backtestReportName);

            if (!$this->isSavedEntity($event))
            {
                return;
            }
        }
    }

    private function writeConfig(ExecutionEvent $event, string $type): bool
    {
        $writeConfigEvent = new WriteConfigEvent($event, $type);
        $this->dispatch($writeConfigEvent);

        if ($writeConfigEvent->hasErrors())
        {
            foreach ($writeConfigEvent->getErrors() as $error)
            {
                $event->addError($error);
            }

            return false;
        }

        return true;
    }

    private function findTerminal(ExecutionEvent $event): bool
    {
        $findTerminalEvent = new FindTerminalEvent($event, $this->containerBag->get('metatrader')['data_path'] ?? '');
        $this->dispatch($findTerminalEvent);

        return $findTerminalEvent->isFound();
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ParameterBag($this->containerBag->get('metatrader')['expert_advisors'][$name] ?? []);

        return new $class($name, $parameters);
    }

    private function isActiveExpertAdvisor(ExecutionEvent $event): bool
    {
        $event->setExpertAdvisor($this->getExpertAdvisorInstance($event->getBacktestEntity()->getExpertAdvisor()));

        return $event->getExpertAdvisor()->isActive();
    }

    private function isFoundBacktestEntity(ExecutionEvent $event): bool
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

    private function isFoundBacktestReportEntity(ExecutionEvent $event, string $backtestReportName): bool
    {
        // TODO: Backtest report name must be a valid unique identifier by itself
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

    private function isSavedEntity(ExecutionEvent $event): bool
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
