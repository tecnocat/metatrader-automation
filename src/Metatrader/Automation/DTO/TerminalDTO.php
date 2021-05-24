<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\DTO;

use Spatie\DataTransferObject\DataTransferObject as DTO;

class TerminalDTO extends DTO
{
    public string $terminalConfig;
    public string $terminalExe;
    public string $terminalId;
    public string $terminalPath;
    public int    $terminalVersion;

    public function isBusy(): bool
    {
        // TODO: Check if this instance is running a backtest or not
        return (bool) rand(0, 10);
    }

    public function isCluster(): bool
    {
        return (bool) preg_match('/\\\\MT[4-5]-\d+\\\\/', $this->terminalExe);
    }

    public function isSupported(): bool
    {
        return 4 === $this->terminalVersion;
    }
}
