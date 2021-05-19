<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\EntityInterface;

class Prudencio extends AbstractExpertAdvisor
{
    public function generateBacktestReportName(EntityInterface $backtestEntity): \Generator
    {
        $fromDate     = clone $backtestEntity->getFrom();
        $toDate       = $backtestEntity->getTo();
        $period       = $backtestEntity->getPeriod();
        $stepMonths   = $this->getParameters()->getInt('step_months');
        $distanceData = $this->getParameters()->get('distance');
        $hedgingData  = $this->getParameters()->get('hedging');
        $profitData   = $this->getParameters()->get('profit');
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
                        $backtestReportName      = "$period-$from-$to-d$distance-h$hedging-l$loss-p$profit-i$increment.html";
                        $currentBacktestSettings = [
                            'period'             => $period,
                            'from'               => $from,
                            'to'                 => $to,
                            'distance'           => $distance,
                            'hedging'            => $hedging,
                            'loss'               => $loss,
                            'profit'             => $profit,
                            'increment'          => $increment,
                            'backtestReportName' => $backtestReportName,
                        ];
                        $this->setCurrentBacktestSettings($currentBacktestSettings);

                        yield $backtestReportName;

                        $distance = $distance + $distanceData['step'];
                    }

                    $hedging += $hedgingData['step'];
                }

                $profit += $profitData['step'];
            }

            $fromDate->modify('+1 month');
        }
    }

    public function getAlias(): array
    {
        return [
            // PHP name    MT4 EA name (.ex4)
            'logLevel'  => 'LogLevel',
            'distance'  => 'Distancia',
            'hedging'   => 'Cobertura',
            'loss'      => 'Perdida',
            'profit'    => 'Beneficio',
            'increment' => 'Exponencial',
            'multiply'  => 'Multiplicador',
        ];
    }
}
