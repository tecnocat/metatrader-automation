<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Domain;

class BacktestIteration implements BacktestIterationInterface
{
    private string $reportName;

    public function __construct(string $reportName)
    {
        $this->reportName = $reportName;
    }

    public function getReportName(): string
    {
        return $this->reportName;
    }
}
