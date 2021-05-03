<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

class ExpertAdvisorConfig
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __get(string $name): ?string
    {
        return $this->config[$name] ?? null;
    }

    public function __set(string $name, string $value): void
    {
        $this->config[$name] = $value;
    }
}
