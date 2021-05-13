<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Interfaces\EntityInterface;

class FindEntityEvent extends AbstractEvent
{
    private string              $class;
    private array           $criteria;
    private EntityInterface $entity;

    public function __construct(string $class, array $criteria)
    {
        $this->class    = $class;
        $this->criteria = $criteria;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }

    public function existsEntity(): bool
    {
        return isset($this->entity);
    }

    public function setEntity(EntityInterface $entity): void
    {
        $this->entity = $entity;
    }
}
