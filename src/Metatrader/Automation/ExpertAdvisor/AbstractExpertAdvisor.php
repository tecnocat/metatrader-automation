<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    protected const METATRADER_DATE_FORMAT = 'Y.m.d';

    private string       $name;
    private ParameterBag $parameters;

    final public function __construct(string $name, ParameterBag $parameters)
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

    final public function getParameters(): ParameterBag
    {
        return $this->parameters;
    }

    final public function isActive(): bool
    {
        return $this->parameters->getBoolean('active');
    }
}
