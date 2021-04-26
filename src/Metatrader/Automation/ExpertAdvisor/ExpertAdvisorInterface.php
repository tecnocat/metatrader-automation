<?php

namespace Metatrader\Automation\ExpertAdvisor;

interface ExpertAdvisorInterface
{
    /**
     * @param string $expertAdvisorName
     *
     * @return string
     */
    public static function getExpertAdvisorClass(string $expertAdvisorName): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return ExpertAdvisorConfig
     */
    public function getConfig(): ExpertAdvisorConfig;
}