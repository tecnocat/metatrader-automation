<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Helper\ClassTools;

abstract class AbstractEvent implements EventInterface, EventErrorInterface
{
    private array $errors;

    final public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    final public function getErrors(): array
    {
        return $this->errors;
    }

    final public function getEventName(): string
    {
        return ClassTools::getClassNameDotted($this);
    }

    final public function hasErrors(): bool
    {
        return isset($this->errors) && 0 !== count($this->errors);
    }
}
