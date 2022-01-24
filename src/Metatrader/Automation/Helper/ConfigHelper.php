<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class ConfigHelper
{
    public const TESTER_CONFIG_FILE = 'tester.ini';
    public const TESTER_CONFIG_PATH = 'tester';
    public const TESTER_FILES_PATH  = 'files';

    public static function getBacktestReportHtmlFile(string $terminalPath, string $backtestReportName, bool $relative = false): string
    {
        $backtestReportHtmlFile = self::TESTER_FILES_PATH . DIRECTORY_SEPARATOR . $backtestReportName . '.html';

        return self::getTerminalFile($terminalPath, str_replace(':', '-', $backtestReportHtmlFile), $relative);
    }

    public static function getExpertAdvisorConfigFile(string $terminalPath, string $expertAdvisorName, bool $relative = false): string
    {
        return self::getTerminalFile($terminalPath, $expertAdvisorName . '.ini', $relative);
    }

    public static function getRelativePath(string $fullPath, string $relativePath): string
    {
        return ltrim(str_replace(rtrim($relativePath, DIRECTORY_SEPARATOR . '/'), '', $fullPath), DIRECTORY_SEPARATOR . '/');
    }

    public static function getTerminalConfigFile(string $terminalPath, bool $relative = false): string
    {
        return self::getTerminalFile($terminalPath, self::TESTER_CONFIG_FILE, $relative);
    }

    public static function getTerminalFile(string $terminalPath, string $filename, bool $relative): string
    {
        return (!$relative ? $terminalPath . DIRECTORY_SEPARATOR : '') . self::TESTER_CONFIG_PATH . DIRECTORY_SEPARATOR . $filename;
    }
}
