<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

use App\Metatrader\Automation\Event\AbstractEvent;

abstract class AbstractBuildEntityEvent extends AbstractEvent implements EventBuildEntityInterface
{
    private array $parameters;

    final public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    final public function getParameters(): array
    {
        return $this->parameters;
    }
}
