<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\Event\AbstractEvent;

class WriteConfigEvent extends AbstractEvent
{
    public const EXPERT_ADVISOR_CONFIG_TYPE = 'expert-advisor';
    public const TERMINAL_CONFIG_TYPE       = 'terminal';

    private ExecutionEvent $executionEvent;
    private string         $type;

    public function __construct(ExecutionEvent $executionEvent, string $type)
    {
        $this->executionEvent = $executionEvent;
        $this->type           = $type;
    }

    public function getExecutionEvent(): ExecutionEvent
    {
        return $this->executionEvent;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
