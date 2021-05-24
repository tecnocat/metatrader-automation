<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\Event\AbstractEvent;

class BuildConfigEvent extends AbstractEvent
{
    public const TERMINAL_CONFIG_TYPE       = 'terminal.ini';
    public const EXPERT_ADVISOR_CONFIG_TYPE = 'expert-advisor.ini';

    private array          $config;
    private ExecutionEvent $executionEvent;
    private string         $type;

    public function __construct(ExecutionEvent $executionEvent, string $type)
    {
        $this->executionEvent = $executionEvent;
        $this->type           = $type;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
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
