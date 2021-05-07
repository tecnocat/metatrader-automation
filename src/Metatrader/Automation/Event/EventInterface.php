<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

interface EventInterface
{
    public function addError(string $error): void;

    public function getErrors(): array;

    public function getEventName(): string;

    public function hasErrors(): bool;
}
