<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
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

    public function onExecutionEvent(MetatraderExecutionEvent $executionEvent): void
    {
        $backtestEntity = $executionEvent->getBacktestEntity();
        $expertAdvisor  = $this->getExpertAdvisorInstance($backtestEntity->getName());

        if (!$expertAdvisor->isActive())
        {
            $executionEvent->addError('The Expert Advisor ' . $expertAdvisor->getName() . ' is not active');

            return;
        }

        foreach ($expertAdvisor->getBacktestReportName($backtestEntity) as $backtestReportName)
        {
            $criteria        = ['name' => $backtestReportName];
            $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                echo $backtestReportName . ' FOUND!' . PHP_EOL;

                continue;
            }

            echo $backtestReportName . ' MISSING!' . PHP_EOL;
        }
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ParameterBag($this->containerBag->get('expert_advisors')[$name] ?? []);

        return new $class($name, $parameters);
    }
}
