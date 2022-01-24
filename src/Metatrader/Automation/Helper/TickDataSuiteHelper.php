<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\DTO\TerminalDTO;

class TickDataSuiteHelper
{
    public const CONTROL_FILE = 'TDS.ok';

    public static function wait(TerminalDTO $terminalDTO): void
    {
        $tickDataSuiteFile = ConfigHelper::getTerminalFile($terminalDTO->path, ConfigHelper::TESTER_FILES_PATH . DIRECTORY_SEPARATOR . self::CONTROL_FILE, false);
        $timeout           = 0;

        while (!file_exists($tickDataSuiteFile) && ++$timeout < 60)
        {
            sleep(1);
        }

        if (file_exists($tickDataSuiteFile))
        {
            unlink($tickDataSuiteFile);
        }
    }
}
