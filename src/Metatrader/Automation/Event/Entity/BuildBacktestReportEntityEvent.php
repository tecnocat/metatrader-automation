<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event\Entity;

use App\Metatrader\Automation\Entity\BacktestReportEntity;

class BuildBacktestReportEntityEvent extends AbstractBuildEntityEvent
{
    private BacktestReportEntity $backtestReportEntity;

    public function getBacktestReportEntity(): BacktestReportEntity
    {
        return $this->backtestReportEntity;
    }

    public function setBacktestReportEntity(BacktestReportEntity $backtestReportEntity): void
    {
        $this->backtestReportEntity = $backtestReportEntity;
    }
}
