<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use Symfony\Component\HttpFoundation\Request;

class PrepareBacktestParametersRequestEvent extends AbstractEvent
{
    private array   $parameters;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
