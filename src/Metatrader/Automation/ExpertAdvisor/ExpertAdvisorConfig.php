<?php

namespace App\Metatrader\Automation\ExpertAdvisor;

use Exception;

/**
 * Class ExpertAdvisorConfig
 *
 * @package App\Metatrader\Automation\ExpertAdvisor
 */
class ExpertAdvisorConfig
{
    /**
     * @var array
     */
    private array $config;

    /**
     * ExpertAdvisorConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (!isset($this->config[$name]))
        {
            throw new Exception('Configuration ' . $name . ' is not supported.');
        }

        return $this->config[$name];
    }

    /**
     * @param string $name
     * @param        $value
     *
     * @throws Exception
     */
    public function __set(string $name, $value)
    {
        if (!isset($this->config[$name]))
        {
            throw new Exception('Configuration ' . $name . ' is not supported.');
        }

        $this->config[$name] = $value;
    }
}