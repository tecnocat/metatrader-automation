<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

class ExpertAdvisorParameters
{
    private array $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return null|mixed
     */
    public function getParameter(string $name)
    {
        return $this->parameters[$name] ?? null;
    }

    public function setParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }
}
