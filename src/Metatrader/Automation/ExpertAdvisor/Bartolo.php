<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\DTO\BacktestDTO;

class Bartolo extends AbstractExpertAdvisor
{
    public function getIteration(BacktestDTO $backtestDTO): \Generator
    {
        foreach ($this->loadIterations($backtestDTO) as $iteration)
        {
            yield $iteration;
        }
    }
}
