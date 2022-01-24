<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class BacktestReportHelper
{
    public const  INPUTS_PARAMETER_PREFIX = 'inputs';
    private const ANY_DATE                = '\d{4}\.\d{2}.\d{2}';
    private const ANY_NUMBER              = '[-+]?\d+[\.|\,]?\d*';
    private const ANY_WORD                = '[\w\s]+';

    private static array $cache;

    public static function getBacktestReportName(array $data): string
    {
        $parameters = [
            $data['expertAdvisorName'],
            $data['symbol'],
            $data['period'],
            $data['from'],
            $data['to'],
            $data['initialDeposit'],
        ];
        $name       = implode(':', $parameters);
        $inputs     = unserialize($data[self::INPUTS_PARAMETER_PREFIX]);
        ksort($inputs);

        foreach ($inputs as $parameterName => $parameterValue)
        {
            $name .= ':' . $parameterName . '-' . $parameterValue;
        }

        return $name;
    }

    public static function isValid(string $file): bool
    {
        $lines = self::loadFile($file);

        return false !== mb_strpos($lines[37], '99.90%') && false !== mb_strpos($lines[37], 'Modelling quality');
    }

    public static function readFile(string $file): array
    {
        $parameters = [];
        self::fixFile($file);

        foreach (self::loadFile($file) as $number => $line)
        {
            foreach (self::parseLine(++$number, trim($line)) as $parameterName => $parameterValue)
            {
                $parameters[$parameterName] = $parameterValue;
            }
        }

        $parameters         = self::transform($parameters);
        $parameters['name'] = self::getBacktestReportName($parameters);

        return $parameters;
    }

    public static function transformParameters(array $parameters): array
    {
        return self::transform($parameters);
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

    private static function evictCache(): void
    {
        self::$cache = [];
    }

    private static function fixFile(string $file): void
    {
        $lines = self::loadFile($file);

        if (false !== mb_strpos($lines[35], ';'))
        {
            $search  = $lines[35];
            $replace = str_replace('; ', '<br>', $search);
            $reading = fopen($file, 'r');
            $writing = fopen($file . '.tmp', 'w');

            while (!feof($reading))
            {
                fputs($writing, str_replace($search, $replace, fgets($reading)));
            }

            fclose($reading);
            fclose($writing);
            rename($file . '.tmp', $file);
            self::evictCache();
        }
    }

    private static function getMatches(string $regex, string $line): array
    {
        preg_match_all($regex, $line, $matches);

        return $matches;
    }

    private static function loadFile(string $file): array
    {
        $cacheKey = md5($file);

        if (!isset(self::$cache[$cacheKey]))
        {
            $handle = new \SplFileObject($file);
            $lines  = [];

            while (!$handle->eof() && count($lines) < 52)
            {
                $lines[] = $handle->fgets();
            }

            self::$cache = [
                $cacheKey => $lines,
            ];
        }

        return self::$cache[$cacheKey];
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
                    $result[self::toAttribute(self::INPUTS_PARAMETER_PREFIX . $matches[1][$index])] = $matches[2][$index];
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

    private static function toAttribute(string $attribute): string
    {
        return lcfirst(str_replace(' ', '', ucwords($attribute)));
    }

    private static function transform(array $parameters): array
    {
        $parameters                   = array_map('trim', $parameters);
        $parameters['from']           = str_replace('.', '-', $parameters['from']);
        $parameters['to']             = str_replace('.', '-', $parameters['to']);
        $parameters['initialDeposit'] = str_replace('.00', '', $parameters['initialDeposit']);

        if (isset($parameters['expertAdvisor']))
        {
            $parameters['expertAdvisorName'] = $parameters['expertAdvisor'];
            unset($parameters['expertAdvisor']);
        }

        if (isset($parameters['spread']) && 'Variable' === $parameters['spread'])
        {
            $parameters['spread'] = -1;
        }

        return self::transformInputs($parameters);
    }

    private static function transformInputs(array $parameters): array
    {
        $inputs = [];

        foreach ($parameters as $parameterName => $parameterValue)
        {
            if (self::INPUTS_PARAMETER_PREFIX === mb_substr($parameterName, 0, mb_strlen(self::INPUTS_PARAMETER_PREFIX)))
            {
                unset($parameters[$parameterName]);
                $inputs[mb_substr($parameterName, mb_strlen(self::INPUTS_PARAMETER_PREFIX))] = $parameterValue;
            }
        }

        if (!empty($inputs))
        {
            ksort($inputs);
            $parameters[self::INPUTS_PARAMETER_PREFIX] = serialize($inputs);
        }

        return $parameters;
    }
}
