<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

interface CommandInterface
{
    public function process(): int;
}
