<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use Symfony\Component\Console\Input\InputInterface;

class PrepareBacktestParametersCommandEvent extends AbstractEvent
{
    private InputInterface $input;
    private array          $parameters;

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}
