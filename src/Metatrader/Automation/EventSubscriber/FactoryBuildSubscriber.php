<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Entity\AbstractBuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\BuildBacktestEntityEvent;
use App\Metatrader\Automation\Event\Entity\BuildBacktestReportEntityEvent;
use App\Metatrader\Automation\Form\Type\BacktestReportType;
use App\Metatrader\Automation\Form\Type\BacktestType;
use App\Metatrader\Automation\Helper\ClassHelper;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @Subscriber
 */
class FactoryBuildSubscriber
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function onBuildBacktestEntityEvent(BuildBacktestEntityEvent $event): void
    {
        $this->handleFormType(BacktestType::class, $event);
    }

    public function onBuildBacktestReportEntityEvent(BuildBacktestReportEntityEvent $event): void
    {
        $this->handleFormType(BacktestReportType::class, $event);
    }

    private function buildForm(string $formType): FormInterface
    {
        return $this->formFactory->createBuilder($formType)->getForm();
    }

    private function handleFormType(string $formType, AbstractBuildEntityEvent $event): void
    {
        $form = $this->buildForm($formType);
        $form->submit($event->getParameters());

        if (!$form->isValid())
        {
            foreach ($form->getErrors(true) as $formError)
            {
                $event->addError(sprintf('%s (%s): %s', $formError->getOrigin()->getName(), $formError->getOrigin()->getViewData(), $formError->getMessage()));
            }

            return;
        }

        call_user_func([$event, 'set' . ClassHelper::getClassName($form->getData())], $form->getData());
    }
}
