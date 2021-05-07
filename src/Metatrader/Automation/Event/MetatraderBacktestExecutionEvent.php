<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Domain\BacktestInterface;
use App\Metatrader\Automation\Domain\BacktestReportInterface;

class MetatraderBacktestExecutionEvent extends AbstractEvent
{
    private BacktestInterface       $backtest;
    private BacktestReportInterface $backtestReport;

    public function __construct(BacktestInterface $backtest)
    {
        $this->backtest = $backtest;
    }

    public function getBacktest(): BacktestInterface
    {
        return $this->backtest;
    }

    public function getBacktestReport(): BacktestReportInterface
    {
        return $this->backtestReport;
    }

    public function setBacktestReport(BacktestReportInterface $backtestReport): void
    {
        $this->backtestReport = $backtestReport;
    }
}
