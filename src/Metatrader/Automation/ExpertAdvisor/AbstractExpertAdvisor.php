<?php

namespace Metatrader\Automation\ExpertAdvisor;

/**
 * Class AbstractExpertAdvisor
 *
 * @package Metatrader\Automation\ExpertAdvisor
 */
class AbstractExpertAdvisor implements ExpertAdvisorInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var ExpertAdvisorConfig
     */
    private ExpertAdvisorConfig $config;

    /**
     * AbstractExpertAdvisor constructor.
     *
     * @param string              $name
     * @param ExpertAdvisorConfig $config
     */
    final public function __construct(string $name, ExpertAdvisorConfig $config)
    {
        $this->name   = $name;
        $this->config = $config;
    }

    /**
     * @param string $expertAdvisorName
     *
     * @return string
     */
    public static function getExpertAdvisorClass(string $expertAdvisorName): string
    {
        return __NAMESPACE__ . '\\' . $expertAdvisorName;
    }

    /**
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ExpertAdvisorConfig
     */
    final public function getConfig(): ExpertAdvisorConfig
    {
        return $this->config;
    }
}