<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\Event\AbstractEvent;

class BuildMetatraderConfigEvent extends AbstractEvent
{
    public const TERMINAL_CONFIG_TYPE       = 'terminal.ini';
    public const EXPERT_ADVISOR_CONFIG_TYPE = 'expert-advisor.ini';

    private array                    $config;
    private MetatraderExecutionEvent $event;
    private string                   $type;

    public function __construct(MetatraderExecutionEvent $event, string $type)
    {
        $this->event = $event;
        $this->type  = $type;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getEvent(): MetatraderExecutionEvent
    {
        return $this->event;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
