<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;

class ConfigHelper
{
    public const TESTER_CONFIG_PATH = 'tester';
    public const TESTER_CONFIG_FILE = 'tester.ini';

    public static function getBacktestReportHtmlFile(string $terminalPath, array $currentBacktestSettings, bool $relative = false): string
    {
        return self::getTerminalFile($terminalPath, $currentBacktestSettings['backtestReportName'], $relative);
    }

    public static function getExpertAdvisorConfigFile(string $terminalPath, string $expertAdvisorName, bool $relative = false): string
    {
        return self::getTerminalFile($terminalPath, $expertAdvisorName . '.ini', $relative);
    }

    public static function getExpertAdvisorInputs(ExpertAdvisorInterface $expertAdvisor): array
    {
        $alias  = $expertAdvisor->getAlias();
        $inputs = [];

        foreach ($expertAdvisor->getCurrentBacktestSettings() as $backtestSettingName => $backtestSettingValue)
        {
            if (!isset($alias[$backtestSettingName]))
            {
                continue;
            }

            $inputs[$alias[$backtestSettingName]] = $backtestSettingValue;
        }

        return $inputs;
    }

    public static function getTerminalConfigFile(string $terminalPath, bool $relative = false): string
    {
        return self::getTerminalFile($terminalPath, self::TESTER_CONFIG_FILE, $relative);
    }

    private static function getRelativePath(string $fullPath, string $relativePath): string
    {
        return ltrim(str_replace(rtrim($relativePath, DIRECTORY_SEPARATOR), '', $fullPath), DIRECTORY_SEPARATOR);
    }

    private static function getTerminalFile(string $terminalPath, string $filename, bool $relative): string
    {
        return (!$relative ? $terminalPath . DIRECTORY_SEPARATOR : '') . self::TESTER_CONFIG_PATH . DIRECTORY_SEPARATOR . $filename;
    }
}
