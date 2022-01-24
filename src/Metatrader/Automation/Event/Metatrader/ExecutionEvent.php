<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\DTO\BacktestDTO;
use App\Metatrader\Automation\DTO\BacktestExecutionDTO;
use App\Metatrader\Automation\DTO\TerminalDTO;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Event\AbstractEvent;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;

class ExecutionEvent extends AbstractEvent
{
    private BacktestDTO            $backtestDTO;
    private BacktestEntity         $backtestEntity;
    private BacktestExecutionDTO   $backtestExecutionDTO;
    private ExpertAdvisorInterface $expertAdvisor;
    private TerminalDTO            $terminalDTO;

    public function __construct(BacktestDTO $backtestDTO)
    {
        $this->backtestDTO = $backtestDTO;
    }

    public function alreadyExecutedBacktest(): bool
    {
        return isset($this->backtestEntity);
    }

    public function getBacktestDTO(): BacktestDTO
    {
        return $this->backtestDTO;
    }

    public function getBacktestEntity(): BacktestEntity
    {
        return $this->backtestEntity;
    }

    public function setBacktestEntity(BacktestEntity $backtestEntity)
    {
        $this->backtestEntity = $backtestEntity;
    }

    public function getBacktestExecutionDTO(): BacktestExecutionDTO
    {
        return $this->backtestExecutionDTO;
    }

    public function setBacktestExecutionDTO(BacktestExecutionDTO $backtestExecutionDTO): void
    {
        $this->backtestExecutionDTO = $backtestExecutionDTO;
    }

    public function getExpertAdvisor(): ExpertAdvisorInterface
    {
        return $this->expertAdvisor;
    }

    public function setExpertAdvisor(ExpertAdvisorInterface $expertAdvisor): void
    {
        $this->expertAdvisor = $expertAdvisor;
    }

    public function getTerminalDTO(): TerminalDTO
    {
        return $this->terminalDTO;
    }

    public function setTerminalDTO(TerminalDTO $terminalDTO): void
    {
        $this->terminalDTO = $terminalDTO;
    }
}
