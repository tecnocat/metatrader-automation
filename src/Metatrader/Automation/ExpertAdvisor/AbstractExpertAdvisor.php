<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    protected const METATRADER_DATE_FORMAT = 'Y.m.d';
    private array        $currentBacktestSettings;
    private string       $name;
    private ParameterBag $parameters;

    final public function __construct(string $name, ParameterBag $parameters)
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    public function getCurrentBacktestSettings(): array
    {
        return $this->currentBacktestSettings;
    }

    final public function setCurrentBacktestSettings(array $currentBacktestSettings): void
    {
        $this->currentBacktestSettings = $currentBacktestSettings;
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

    final public function iterate(array $array): \Generator
    {
        foreach ((array) array_pop($array) as $value)
        {
            if (count($array))
            {
                foreach ($this->iterate($array) as $combination)
                {
                    yield array_merge([$value], $combination);
                }
            }
            else
            {
                yield [$value];
            }
        }
    }
}
