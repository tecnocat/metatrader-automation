<?php

namespace App\Metatrader\Automation\Event;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class PrepareBacktestParametersEvent
 *
 * @package App\Metatrader\Automation\Event
 */
class PrepareBacktestParametersCommandEvent extends AbstractEvent
{
    /**
     * @var InputInterface
     */
    private InputInterface $input;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * PrepareBacktestParametersEvent constructor.
     *
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
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
}