<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @Subscriber
 */
class ExpertAdvisorSubscriber extends AbstractEventSubscriber
{
    /**
     * @Dependency
     */
    public ContainerBagInterface $containerBag;

    public function onExecutionEvent(ExecutionEvent $event): void
    {
        $event->setExpertAdvisor($this->getExpertAdvisorInstance($event->getBacktestDTO()->expertAdvisorName));
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ParameterBag($this->containerBag->get('metatrader')['expert_advisors'][$name] ?? []);

        return new $class($name, $parameters);
    }
}
