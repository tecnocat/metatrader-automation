<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class WindowsHelper
{
    private static int $cores;

    public static function getNumberOfCores(): int
    {
        if (!isset(self::$cores))
        {
            // NumberOfCores NumberOfEnabledCore NumberOfLogicalProcessors ThreadCount
            // 4             4                   8                         8
            self::$cores = 1;
            $wmic        = popen('wmic cpu get NumberOfLogicalProcessors', 'rb');

            if (false !== $wmic)
            {
                fgets($wmic);
                self::$cores = intval(fgets($wmic));
                pclose($wmic);
            }
        }

        return self::$cores;
    }

    public static function getTerminalsRunning(): array
    {
        $terminalsRunning = [];
        exec('PowerShell Get-Process ^| Format-List Path 2> NUL', $terminalsRunning);
        $customHalt       = preg_grep('/notepad\.exe/', $terminalsRunning);
        $terminalsRunning = preg_grep(TerminalHelper::TERMINAL_CLUSTER_EXE_PATTERN, $terminalsRunning);

        if (!empty($customHalt))
        {
            throw new \RuntimeException('Detected notepad.exe, stop the current process now!');
        }

        return array_map(function ($path)
        {
            // @codeCoverageIgnoreStart
            $parts = explode(' ', $path);

            return end($parts); // @codeCoverageIgnoreEnd
        }, $terminalsRunning);
    }
}
