<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Helper\BacktestReportHelper;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    protected const METATRADER_DATE_FORMAT = 'Y.m.d';
    private array        $currentBacktestSettings;
    private string       $name;
    private ParameterBag $parameters;

    final public function __construct(string $name, ParameterBag $parameters)
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    public function getCurrentBacktestSettings(): array
    {
        return $this->currentBacktestSettings;
    }

    final public function setCurrentBacktestSettings(array $currentBacktestSettings): void
    {
        $this->currentBacktestSettings = $currentBacktestSettings;
    }

    final public static function getExpertAdvisorClass(string $expertAdvisorName): string
    {
        return __NAMESPACE__ . '\\' . $expertAdvisorName;
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

    final protected function dateRangeIterator(\DateTime $from, \DateTime $to, int $stepMonths): \Generator
    {
        $fromDate = clone $from;
        $toDate   = clone $to;

        while ($fromDate < $toDate)
        {
            $limitDate = (clone $fromDate)->modify("+$stepMonths month");

            if ($limitDate > $toDate && 1 < $stepMonths)
            {
                --$stepMonths;

                continue;
            }

            yield [
                'from' => $fromDate->format(self::METATRADER_DATE_FORMAT),
                'to'   => $limitDate->format(self::METATRADER_DATE_FORMAT),
            ];

            $fromDate->modify('+1 month');
        }
    }

    final protected function getBacktestReportName(array $backtestSettings): string
    {
        // TODO: This sounds bad... Is really needed?
        $this->setCurrentBacktestSettings($backtestSettings);

        return BacktestReportHelper::getBacktestReportName($backtestSettings);
    }

    final protected function iterate(array $array): \Generator
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

    final protected function minMaxIterator(string $name, array $range): \Generator
    {
        foreach (range($range['min'], $range['max'], $range['step']) as $value)
        {
            yield [$name => $value];
        }
    }

    final protected function simpleIterator(string $name, array $values): \Generator
    {
        foreach ($values as $value)
        {
            yield [$name => $value];
        }
    }
}
