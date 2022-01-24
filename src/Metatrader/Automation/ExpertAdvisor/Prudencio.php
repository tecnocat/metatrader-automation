<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\DTO\BacktestDTO;
use App\Metatrader\Automation\Helper\BacktestReportHelper;

class Prudencio extends AbstractExpertAdvisor
{
    public function getIteration(BacktestDTO $backtestDTO): \Generator
    {
        foreach ($this->loadIterations($backtestDTO) as $iteration)
        {
            $prefix                         = BacktestReportHelper::INPUTS_PARAMETER_PREFIX;
            $iteration[$prefix . 'Perdida'] = $iteration[$prefix . 'Cobertura'] * 2;

            yield $iteration;
        }
    }
}
