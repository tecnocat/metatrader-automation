<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\FindEntityEvent;
use App\Metatrader\Automation\Interfaces\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Subscriber
 */
class EntitySubscriber extends AbstractEventSubscriber
{
    private EntityManagerInterface $entityManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager)
    {
        parent::__construct($eventDispatcher);

        $this->entityManager = $entityManager;
    }

    public function onFindEntityEvent(FindEntityEvent $event): void
    {
        $repository = $this->entityManager->getRepository($event->getClass());
        $criteria   = $event->getCriteria();

        /** @var EntityInterface $entity */
        if (null !== $entity = $repository->findOneBy($criteria))
        {
            $event->setEntity($entity);
        }
    }
}
