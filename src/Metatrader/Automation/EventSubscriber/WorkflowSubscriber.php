<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;
use App\Metatrader\Automation\Event\Metatrader\FindTerminalEvent;
use App\Metatrader\Automation\Event\Metatrader\WriteConfigEvent;
use App\Metatrader\Automation\Helper\TickDataSuiteHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

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
        if (!$event->getExpertAdvisor()->isActive())
        {
            $event->addError('The Expert Advisor ' . $event->getExpertAdvisor()->getName() . ' is not active');

            return;
        }

        if ($event->alreadyExecutedBacktest())
        {
            $lastBacktestReportName = $event->getBacktestEntity()->getLastBacktestReportName();
        }
        else
        {
            $buildEntityEvent = new BuildEntityEvent(BacktestEntity::class, $event->getBacktestDTO()->toParameters());
            $this->dispatch($buildEntityEvent);

            if ($buildEntityEvent->hasErrors())
            {
                $event->addError('Unable to build backtest entity');

                foreach ($buildEntityEvent->getErrors() as $error)
                {
                    $event->addError($error);
                }

                return;
            }

            /** @var BacktestEntity $backtestEntity */
            $backtestEntity = $buildEntityEvent->getEntity();
            $event->setBacktestEntity($backtestEntity);
        }

        foreach ($event->getExpertAdvisor()->getIteration($event->getBacktestDTO()) as $iteration)
        {
            $backtestExecutionDTO = $event->getExpertAdvisor()->getBacktestExecutionDTO($event->getBacktestDTO(), $iteration);

            if (!isset($continue) && !empty($lastBacktestReportName) && $lastBacktestReportName !== $backtestExecutionDTO->name)
            {
                continue;
            }

            $event->setBacktestExecutionDTO($backtestExecutionDTO);
            $continue        = true;
            $criteria        = ['name' => $backtestExecutionDTO->name];
            $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                continue;
            }

            if (!$this->findTerminal($event))
            {
                $event->addError('Unable to find free Terminal');

                return;
            }

            if (!$this->writeConfig($event, WriteConfigEvent::TERMINAL_CONFIG_TYPE))
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
            pclose(popen(sprintf('start /b %s %s', $terminalDTO->exe, $terminalDTO->config), 'r'));
            TickDataSuiteHelper::wait($terminalDTO);

            $event->getBacktestEntity()->setLastBacktestReportName($backtestExecutionDTO->name);

            if (!$this->isSavedEntity($event))
            {
                return;
            }
        }
    }

    private function findTerminal(ExecutionEvent $event): bool
    {
        $findTerminalEvent = new FindTerminalEvent($event, $this->containerBag->get('metatrader')['data_path'] ?? '');
        $this->dispatch($findTerminalEvent);

        return $findTerminalEvent->isFound();
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
}
