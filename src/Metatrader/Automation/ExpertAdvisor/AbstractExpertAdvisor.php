<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    private ExpertAdvisorConfig $config;
    private string              $name;

    final public function __construct(string $name, ExpertAdvisorConfig $config)
    {
        $this->name   = $name;
        $this->config = $config;
    }

    final public function getConfig(): ExpertAdvisorConfig
    {
        return $this->config;
    }

    public static function getExpertAdvisorClass(string $expertAdvisorName): string
    {
        return __NAMESPACE__ . '\\' . $expertAdvisorName;
    }

    final public function getName(): string
    {
        return $this->name;
    }
}
