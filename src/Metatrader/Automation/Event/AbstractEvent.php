<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Helper\ClassHelper;
use App\Metatrader\Automation\Interfaces\ErrorInterface;
use App\Metatrader\Automation\Interfaces\EventInterface;

abstract class AbstractEvent implements EventInterface, ErrorInterface
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

    final public function hasErrors(): bool
    {
        return isset($this->errors) && 0 !== count($this->errors);
    }

    final public function getEventName(): string
    {
        return str_replace('.event', '', ClassHelper::getClassNameDot($this));
    }
}
