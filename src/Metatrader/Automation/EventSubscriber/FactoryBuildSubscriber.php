<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Domain\Backtest;
use App\Metatrader\Automation\Event\FactoryBuildBacktestEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use App\Metatrader\Automation\ExpertAdvisor\ExpertAdvisorConfig;

/**
 * @Subscriber
 */
class FactoryBuildSubscriber
{
    public function onBacktestEvent(FactoryBuildBacktestEvent $event): void
    {
        $model    = $event->getModel();
        $backtest = new Backtest();
        $backtest->setExpertAdvisor($this->getExpertAdvisorInstance($model->getName()));
        $backtest->setSymbol($model->getSymbol());
        $backtest->setPeriod($model->getPeriod());
        $backtest->setDeposit($model->getDeposit());
        $backtest->setFrom($model->getFrom());
        $backtest->setTo($model->getTo());
        $event->setBacktest($backtest);
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class = AbstractExpertAdvisor::getExpertAdvisorClass($name);

        // TODO: Load config from .yaml
        $config = new ExpertAdvisorConfig([]);

        return new $class($name, $config);
    }
}
