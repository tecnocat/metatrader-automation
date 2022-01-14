<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\EntityInterface;

class Prudencio extends AbstractExpertAdvisor
{
    public function generateBacktestReportName(EntityInterface $backtestEntity): \Generator
    {
        $iterations = [
            // TODO: How to fix 'Cannot traverse an already closed generator' ?
            iterator_to_array($this->dateRangeIterator($backtestEntity->getFrom(), $backtestEntity->getTo(), $this->getParameters()->getInt('step_months'))),
            iterator_to_array($this->minMaxIterator('distance', $this->getParameters()->get('distance'))),
            iterator_to_array($this->minMaxIterator('hedging', $this->getParameters()->get('hedging'))),
            iterator_to_array($this->minMaxIterator('profit', $this->getParameters()->get('profit'))),
            iterator_to_array($this->simpleIterator('period', [$backtestEntity->getPeriod()])),
            iterator_to_array($this->simpleIterator('increment', [0])),
        ];

        foreach ($this->iterate($iterations) as $iteration)
        {
            $iteration['loss'] = $iteration['hedging'] * 2;

            yield $this->getBacktestReportName($iteration);
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
