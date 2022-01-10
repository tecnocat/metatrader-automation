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
    private bool  $busy;

    public function __construct(array $parameters = [])
    {
        $this->setFree();

        parent::__construct($parameters);
    }

    public function isBusy(): bool
    {
        return $this->busy;
    }

    public function setBusy(): void
    {
        $this->busy = true;
    }

    public function setFree(): void
    {
        $this->busy = false;
    }
}
