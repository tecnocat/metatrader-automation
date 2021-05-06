<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Domain\Backtest;
use App\Metatrader\Automation\Event\FactoryBuildBacktestEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use App\Metatrader\Automation\ExpertAdvisor\ExpertAdvisorParameters;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @Subscriber
 */
class FactoryBuildSubscriber
{
    private ContainerBagInterface $parameters;

    public function __construct(ContainerBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }

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
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ExpertAdvisorParameters($this->parameters->get('expert_advisors')[$name] ?? []);

        return new $class($name, $parameters);
    }
}
