<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class BacktestReportHelper
{
    private const ANY_DATE   = '\d{4}\.\d{2}.\d{2}';
    private const ANY_NUMBER = '[-+]?\d+[\.|\,]?\d*';
    private const ANY_WORD   = '[\w\s]+';

    public static function getBacktestReportName(array $backtestSettings): string
    {
        ksort($backtestSettings);

        $backtestReportName = $backtestSettings['period'] . '-' . $backtestSettings['from'] . '-' . $backtestSettings['to'];

        foreach ($backtestSettings as $parameterName => $parameterValue)
        {
            switch ($parameterName)
            {
                case 'from':
                case 'period':
                case 'to':
                    break;

                default:
                    $backtestReportName .= '-' . mb_substr($parameterName, 0, 1) . $parameterValue;
            }
        }

        return $backtestReportName . '.html';
    }

    public static function normalizeBacktestReportName(string $backtestReportName): string
    {
        $backtestParameters = [];
        $periods            = ['M1', 'M5', 'M15', 'M30', 'H1', 'H4', 'D1', 'W1', 'MN1'];

        foreach (explode('-', str_replace('.html', '', $backtestReportName)) as $parameter)
        {
            if (in_array($parameter, $periods, true))
            {
                $backtestParameters['period'] = $parameter;

                continue;
            }

            $date = \DateTime::createFromFormat(TerminalHelper::TERMINAL_DATE_FORMAT, $parameter);

            if ($date instanceof \DateTime)
            {
                $secondDate = isset($firstDate) ? $date : null;
                $firstDate  = $firstDate ?? $date;

                continue;
            }

            $backtestParameters[strtolower(mb_substr($parameter, 0, 1))] = mb_substr($parameter, 1);
        }

        if (isset($firstDate, $secondDate))
        {
            if ($firstDate < $secondDate)
            {
                $backtestParameters['from'] = $firstDate->format(TerminalHelper::TERMINAL_DATE_FORMAT);
                $backtestParameters['to']   = $secondDate->format(TerminalHelper::TERMINAL_DATE_FORMAT);
            }
            elseif ($firstDate > $secondDate)
            {
                $backtestParameters['from'] = $secondDate->format(TerminalHelper::TERMINAL_DATE_FORMAT);
                $backtestParameters['to']   = $firstDate->format(TerminalHelper::TERMINAL_DATE_FORMAT);
            }
        }

        return self::getBacktestReportName($backtestParameters);
    }

    public static function parseFile(string $file): array
    {
        $parameters = [
            'name' => self::normalizeBacktestReportName(basename($file)),
        ];

        foreach (self::readFile($file) as $number => $line)
        {
            foreach (self::parseLine(++$number, trim($line)) as $parameterName => $parameterValue)
            {
                $parameters[$parameterName] = $parameterValue;
            }

            if (52 === $number)
            {
                break;
            }
        }

        return self::transforms($parameters);
    }

    private static function commonParser(string $line, string $regex): array
    {
        $matches = self::getMatches($regex, $line);
        $result  = [];

        while ($attribute = array_shift($matches[1]))
        {
            $result[self::toAttribute($attribute)] = array_shift($matches[1]);
        }

        return $result;
    }

    private static function getMatches(string $regex, string $line): array
    {
        preg_match_all($regex, $line, $matches);

        return $matches;
    }

    private static function parseLine(int $number, string $line): array
    {
        $d = self::ANY_DATE;
        $n = self::ANY_NUMBER;
        $w = self::ANY_WORD;

        switch ($number)
        {
            case 29:
                return [
                    'expertAdvisor' => strip_tags($line),
                ];

            case 33:
                return self::commonParser($line, "/>(\.?$w)(?:\s*\(($w)\))?</");

            case 34:
                $regex   = "/>$w\(($w)\).*\(($d)\s+-\s+($d)\)</";
                $matches = self::getMatches($regex, $line);

                return [
                    'period' => $matches[1][0],
                    'from'   => $matches[2][0],
                    'to'     => $matches[3][0],
                ];

            case 36:
                $regex   = "/>($w)=($w)</";
                $matches = self::getMatches($regex, $line);
                $result  = [];

                foreach ($matches[0] as $index => $match)
                {
                    $result[self::toAttribute('input' . $matches[1][$index])] = $matches[2][$index];
                }

                return $result;

            case 35:
            case 38:
            case 39:
            case 41:
            case 42:
            case 43:
            case 44:
            case 46:
            case 47:
                return self::commonParser($line, "/>($w|$n)%?(\s*\(%?(?:$w|$n)%?\))?</");

            case 48:
            case 49:
            case 50:
            case 51:
            case 52:
                $regex   = "/>($w|$n)%?(\s*\(%?(?:$w|$n)%?\))?</";
                $matches = self::getMatches($regex, $line);
                $prefix  = array_shift($matches[1]);
                $result  = [];

                while ($attribute = array_shift($matches[1]))
                {
                    $result[self::toAttribute("$prefix $attribute")] = array_shift($matches[1]);
                }

                return $result;

            default:
                return [];
        }
    }

    private static function readFile(string $file): \Generator
    {
        $handle = new \SplFileObject($file);

        while (!$handle->eof())
        {
            yield $handle->fgets();
        }

        // @codeCoverageIgnoreStart
        $handle = null;
    }

    // @codeCoverageIgnoreEnd

    private static function toAttribute(string $attribute): string
    {
        return lcfirst(str_replace(' ', '', ucwords($attribute)));
    }

    private static function transforms(array $parameters): array
    {
        $parameters                   = array_map('trim', $parameters);
        $parameters['from']           = str_replace('.', '-', $parameters['from']);
        $parameters['to']             = str_replace('.', '-', $parameters['to']);
        $parameters['initialDeposit'] = str_replace('.00', '', $parameters['initialDeposit']);

        if ('Variable' === $parameters['spread'])
        {
            $parameters['spread'] = -1;
        }

        $inputs = [];

        foreach ($parameters as $parameterName => $parameterValue)
        {
            if ('input' === mb_substr($parameterName, 0, 5))
            {
                unset($parameters[$parameterName]);
                $inputs[mb_substr($parameterName, 5)] = $parameterValue;
            }
        }

        $parameters['parameters'] = serialize($inputs);

        return $parameters;
    }
}
