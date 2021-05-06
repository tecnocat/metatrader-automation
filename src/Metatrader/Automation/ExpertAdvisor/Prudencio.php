<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Domain\BacktestInterface;
use App\Metatrader\Automation\Domain\BacktestIteration;

class Prudencio extends AbstractExpertAdvisor
{
    public function getBacktestGenerator(BacktestInterface $backtest): \Generator
    {
        $fromDate     = clone $backtest->getFrom();
        $toDate       = $backtest->getTo();
        $period       = $backtest->getPeriod();
        $stepMonths   = $this->getParameters()->getParameter('step_months');
        $distanceData = $this->getParameters()->getParameter('distance');
        $hedgingData  = $this->getParameters()->getParameter('hedging');
        $profitData   = $this->getParameters()->getParameter('profit');
        $increment    = 0;

        while ($fromDate < $toDate)
        {
            $limitDate = (clone $fromDate)->modify("+$stepMonths month");

            if ($limitDate > $toDate && 1 < $stepMonths)
            {
                --$stepMonths;

                continue;
            }

            $from   = $fromDate->format(self::METATRADER_DATE_FORMAT);
            $to     = $limitDate->format(self::METATRADER_DATE_FORMAT);
            $profit = $profitData['min'];

            while ($profit <= $profitData['max'])
            {
                $hedging = $hedgingData['min'];

                while ($hedging <= $hedgingData['max'])
                {
                    $distance = $distanceData['min'];
                    $loss     = $hedging * 2;

                    while ($distance <= $distanceData['max'])
                    {
                        $reportName = "$period-$from-$to-d$distance-h$hedging-l$loss-p$profit-i$increment.html";

                        yield new BacktestIteration($reportName);

                        $distance = $distance + $distanceData['step'];
                    }

                    $hedging += $hedgingData['step'];
                }

                $profit += $profitData['step'];
            }

            $fromDate->modify('+1 month');
        }
    }
}
