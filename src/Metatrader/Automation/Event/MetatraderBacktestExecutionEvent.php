<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Domain\BacktestInterface;
use App\Metatrader\Automation\Domain\BacktestReportInterface;

class MetatraderBacktestExecutionEvent extends AbstractEvent
{
    private BacktestInterface       $backtest;
    private BacktestReportInterface $backtestReport;
    private array                   $errors;

    public function __construct(BacktestInterface $backtest)
    {
        $this->backtest = $backtest;
    }

    public function addError(string $error): void
    {
        $this->errors[] = $error;
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

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return isset($this->errors) && 0 !== count($this->errors);
    }
}
