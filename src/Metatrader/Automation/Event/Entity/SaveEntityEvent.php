<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

use App\Metatrader\Automation\Event\AbstractEvent;
use App\Metatrader\Automation\Interfaces\EntityInterface;

class SaveEntityEvent extends AbstractEvent
{
    private EntityInterface $entity;

    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
