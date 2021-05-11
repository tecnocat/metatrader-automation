<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Entity\AbstractBuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\FactoryBuildBacktestEntityEvent;
use App\Metatrader\Automation\Event\Entity\FactoryBuildBacktestReportEntityEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use App\Metatrader\Automation\ExpertAdvisor\ExpertAdvisorParameters;
use App\Metatrader\Automation\Form\Type\BacktestReportType;
use App\Metatrader\Automation\Form\Type\BacktestType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @Subscriber
 */
class FactoryBuildSubscriber
{
    private FormFactoryInterface  $formFactory;
    private ContainerBagInterface $parameters;

    public function __construct(ContainerBagInterface $parameters, FormFactoryInterface $formFactory)
    {
        $this->parameters  = $parameters;
        $this->formFactory = $formFactory;
    }

    public function onBuildBacktestEntityEvent(FactoryBuildBacktestEntityEvent $event): void
    {
        $this->handleFormType(BacktestType::class, $event, 'setBacktestEntity');
    }

    public function onBuildBacktestReportEntityEvent(FactoryBuildBacktestReportEntityEvent $event): void
    {
        $this->handleFormType(BacktestReportType::class, $event, 'setBacktestReportEntity');
    }

    private function buildForm(string $formType): FormInterface
    {
        return $this->formFactory->createBuilder($formType)->getForm();
    }

    private function getExpertAdvisorInstance(string $name): AbstractExpertAdvisor
    {
        $class      = AbstractExpertAdvisor::getExpertAdvisorClass($name);
        $parameters = new ExpertAdvisorParameters($this->parameters->get('expert_advisors')[$name] ?? []);

        return new $class($name, $parameters);
    }

    private function handleFormType(string $formType, AbstractBuildEntityEvent $event, string $callback): void
    {
        $form = $this->buildForm($formType);
        $form->submit($event->getParameters());

        if (!$form->isValid())
        {
            foreach ($form->getErrors() as $formError)
            {
                $event->addError($formError->getMessage());
            }

            return;
        }

        call_user_func([$event, $callback], $form->getData());
    }
}
