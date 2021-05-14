<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Entity\BacktestEntity;
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

        $backtestGenerator = $expertAdvisor->getBacktestGenerator($backtestEntity);

        while ($backtestGenerator->valid())
        {
            $criteria        = ['name' => $backtestGenerator->current()];
            $findEntityEvent = new FindEntityEvent(BacktestEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                echo $backtestGenerator->current() . ' FOUND!' . PHP_EOL;
                $backtestGenerator->next();

                continue;
            }

            echo $backtestGenerator->current() . ' MISSING!' . PHP_EOL;

            $backtestGenerator->next();
        }
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ParameterBag($this->containerBag->get('expert_advisors')[$name] ?? []);

        return new $class($name, $parameters);
    }
}
