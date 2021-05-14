<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Entity\BacktestReportEntity;

class MetatraderExecutionEvent extends AbstractEvent
{
    private BacktestEntity       $backtestEntity;
    private BacktestReportEntity $backtestReportEntity;

    public function __construct(BacktestEntity $backtestEntity)
    {
        $this->backtestEntity = $backtestEntity;
    }

    public function getBacktestEntity(): BacktestEntity
    {
        return $this->backtestEntity;
    }

    public function getBacktestReportEntity(): BacktestReportEntity
    {
        return $this->backtestReportEntity;
    }

    public function setBacktestReportEntity(BacktestReportEntity $backtestReportEntity): void
    {
        $this->backtestReportEntity = $backtestReportEntity;
    }
}
