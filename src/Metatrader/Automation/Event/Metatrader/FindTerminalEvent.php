<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\DTO\TerminalDTO;
use App\Metatrader\Automation\Event\AbstractEvent;

class FindTerminalEvent extends AbstractEvent
{
    private string                $dataPath;
    private ExecutionEvent $executionEvent;
    private TerminalDTO    $terminalDTO;

    public function __construct(ExecutionEvent $executionEvent, string $dataPath)
    {
        $this->executionEvent = $executionEvent;
        $this->dataPath       = $dataPath;
    }

    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    public function getExecutionEvent(): ExecutionEvent
    {
        return $this->executionEvent;
    }

    public function getTerminalDTO(): TerminalDTO
    {
        return $this->terminalDTO;
    }

    public function setTerminalDTO(TerminalDTO $terminalDTO): void
    {
        $this->terminalDTO = $terminalDTO;
    }

    public function isFound(): bool
    {
        return isset($this->terminalDTO);
    }
}
