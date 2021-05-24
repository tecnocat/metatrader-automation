<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Metatrader;

use App\Metatrader\Automation\DTO\TerminalDTO;
use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\AbstractEvent;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;

class ExecutionEvent extends AbstractEvent
{
    private BacktestEntity         $backtestEntity;
    private BacktestReportEntity   $backtestReportEntity;
    private ExpertAdvisorInterface $expertAdvisor;
    private TerminalDTO            $terminalDTO;

    public function __construct(BacktestEntity $backtestEntity)
    {
        $this->backtestEntity = $backtestEntity;
    }

    public function getBacktestEntity(): BacktestEntity
    {
        return $this->backtestEntity;
    }

    public function setBacktestEntity(BacktestEntity $backtestEntity): void
    {
        $this->backtestEntity = $backtestEntity;
    }

    public function getBacktestReportEntity(): BacktestReportEntity
    {
        return $this->backtestReportEntity;
    }

    public function setBacktestReportEntity(BacktestReportEntity $backtestReportEntity): void
    {
        $this->backtestReportEntity = $backtestReportEntity;
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
