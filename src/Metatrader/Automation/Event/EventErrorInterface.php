<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

interface EventErrorInterface
{
    public function addError(string $error): void;

    public function getErrors(): array;

    public function hasErrors(): bool;
}
