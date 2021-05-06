<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

abstract class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    protected const METATRADER_DATE_FORMAT = 'Y.m.d';

    private string                  $name;
    private ExpertAdvisorParameters $parameters;

    final public function __construct(string $name, ExpertAdvisorParameters $parameters)
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    final public static function getExpertAdvisorClass(string $expertAdvisorName): string
    {
        return __NAMESPACE__ . '\\' . $expertAdvisorName;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function getParameters(): ExpertAdvisorParameters
    {
        return $this->parameters;
    }

    final public function isActive(): bool
    {
        return (bool) $this->parameters->getParameter('active');
    }
}
