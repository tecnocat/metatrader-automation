<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\DTO;

class TerminalDTO extends AbstractDTO
{
    public string $config;
    public string $exe;
    public string $id;
    public string $path;
    public int    $version;
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
