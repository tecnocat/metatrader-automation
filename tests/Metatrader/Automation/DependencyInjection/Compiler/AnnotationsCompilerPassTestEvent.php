<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\Event\AbstractEvent;

class AnnotationsCompilerPassTestEvent extends AbstractEvent
{
    private string $test;

    public function __construct(string $test)
    {
        $this->test = $test;
    }

    public function getTest(): string
    {
        return $this->test;
    }
}
