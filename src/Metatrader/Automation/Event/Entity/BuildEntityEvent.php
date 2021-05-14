<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

use App\Metatrader\Automation\Event\AbstractEvent;
use App\Metatrader\Automation\Interfaces\EntityInterface;

class BuildEntityEvent extends AbstractEvent
{
    private string          $class;
    private EntityInterface $entity;
    private array           $parameters;

    public function __construct(string $class, array $parameters)
    {
        $this->class      = $class;
        $this->parameters = $parameters;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }

    public function setEntity(EntityInterface $entity): void
    {
        $this->entity = $entity;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
