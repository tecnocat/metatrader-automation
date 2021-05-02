<?php

namespace App\Metatrader\Automation\Event;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PrepareBacktestParametersEvent
 *
 * @package App\Metatrader\Automation\Event
 */
class PrepareBacktestParametersRequestEvent extends AbstractEvent
{
    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * PrepareBacktestRequestParametersEvent constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}