<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

interface EventBuildEntityInterface
{
    public function getParameters(): array;
}
