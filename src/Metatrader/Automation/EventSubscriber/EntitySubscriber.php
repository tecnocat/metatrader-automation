<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Helper\FormHelper;
use App\Metatrader\Automation\Interfaces\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @Subscriber
 */
class EntitySubscriber extends AbstractEventSubscriber
{
    /**
     * @Dependency
     */
    public EntityManagerInterface $entityManager;

    /**
     * @Dependency
     */
    public FormFactoryInterface $formFactory;

    public function onBuildEntityEvent(BuildEntityEvent $event): void
    {
        $form = $this->formFactory->createBuilder(FormHelper::getFormEntityType($event->getClass()))->getForm();
        $form->submit($event->getParameters());

        if (!$form->isValid())
        {
            foreach ($form->getErrors(true) as $formError)
            {
                $event->addError(sprintf('%s (%s): %s', $formError->getOrigin()->getName(), $formError->getOrigin()->getViewData(), $formError->getMessage()));
            }

            return;
        }

        $event->setEntity($form->getData());
    }

    public function onFindEntityEvent(FindEntityEvent $event): void
    {
        /** @var EntityInterface $entity */
        if (null !== $entity = $this->entityManager->getRepository($event->getClass())->findOneBy($event->getCriteria()))
        {
            $event->setEntity($entity);
        }
    }

    public function onSaveEntityEvent(SaveEntityEvent $event): void
    {
        $this->entityManager->persist($event->getEntity());
        $this->entityManager->flush();

        // TODO: What happens when batching a lot of entities? Comment while thinking about that
        //$this->entityManager->clear();
    }
}
