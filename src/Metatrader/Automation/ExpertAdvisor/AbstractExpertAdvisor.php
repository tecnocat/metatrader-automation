<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\DTO\BacktestDTO;
use App\Metatrader\Automation\DTO\BacktestExecutionDTO;
use App\Metatrader\Automation\Helper\BacktestReportHelper;
use App\Metatrader\Automation\Helper\TerminalHelper;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    private string       $name;
    private ParameterBag $parameters;

    final public function __construct(string $name, ParameterBag $parameters)
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    final public static function getExpertAdvisorClass(string $expertAdvisorName): string
    {
        return __NAMESPACE__ . '\\' . $expertAdvisorName;
    }

    final public function getBacktestExecutionDTO(BacktestDTO $backtestDTO, array $iteration): BacktestExecutionDTO
    {
        $parameters = BacktestReportHelper::transformParameters(array_merge($backtestDTO->toParameters(), $iteration));

        return new BacktestExecutionDTO($parameters);
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function getParameters(): ParameterBag
    {
        return $this->parameters;
    }

    final public function isActive(): bool
    {
        return $this->parameters->getBoolean('active');
    }

    final protected function loadIterations(BacktestDTO $backtestDTO): \Generator
    {
        // TODO: How to fix 'Cannot traverse an already closed generator' ?
        // TODO: Try https://github.com/PatchRanger/cartesian-iterator
        $iterations = [iterator_to_array($this->dateRangeIterator($backtestDTO->from, $backtestDTO->to))];

        foreach ($this->getParameters()->get('inputs') as $inputName => $inputData)
        {
            if (is_array($inputData) || (!is_array($inputData) && false === mb_strpos((string) $inputData, ',')))
            {
                $iterations[] = iterator_to_array($this->simpleIterator(BacktestReportHelper::INPUTS_PARAMETER_PREFIX . $inputName, (array) $inputData));

                continue;
            }

            [$min, $max, $step] = array_map('trim', explode(',', $inputData));
            $range              = [
                'min'  => $min,
                'max'  => $max,
                'step' => $step,
            ];
            $iterations[] = iterator_to_array($this->minMaxIterator(BacktestReportHelper::INPUTS_PARAMETER_PREFIX . $inputName, $range));
        }

        return $this->iterate($iterations);
    }

    private function dateRangeIterator(\DateTime $from, \DateTime $to): \Generator
    {
        $fromDate   = clone $from;
        $toDate     = clone $to;
        $stepMonths = 12;

        // We need the full date range on first pass to caching all the ticks data
        yield [
            'from' => $fromDate->format(TerminalHelper::TERMINAL_DATE_FORMAT),
            'to'   => $toDate->format(TerminalHelper::TERMINAL_DATE_FORMAT),
        ];

        while ($fromDate < $toDate)
        {
            $limitDate = (clone $fromDate)->modify("+$stepMonths month");

            if ($limitDate > $toDate && 1 < $stepMonths)
            {
                --$stepMonths;

                continue;
            }

            yield [
                'from' => $fromDate->format(TerminalHelper::TERMINAL_DATE_FORMAT),
                'to'   => $limitDate->format(TerminalHelper::TERMINAL_DATE_FORMAT),
            ];

            $fromDate->modify('+1 month');
        }
    }

    private function iterate(array $array): \Generator
    {
        foreach (array_pop($array) as $value)
        {
            if (count($array))
            {
                foreach ($this->iterate($array) as $combination)
                {
                    yield array_merge($value, $combination);
                }
            }
            else
            {
                yield $value;
            }
        }
    }

    private function minMaxIterator(string $name, array $range): \Generator
    {
        if ($range['min'] === $range['max'])
        {
            yield [$name => (int) $range['min']];
        }
        else
        {
            foreach (range($range['min'], $range['max'], $range['step']) as $value)
            {
                yield [$name => $value];
            }
        }
    }

    private function simpleIterator(string $name, array $values): \Generator
    {
        foreach ($values as $value)
        {
            yield [$name => $value];
        }
    }
}
