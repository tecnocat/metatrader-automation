<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

interface BuildEntityEventInterface
{
    public function getParameters(): array;
}
