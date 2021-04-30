<?php

namespace App\Metatrader\Automation\ExpertAdvisor;

/**
 * Interface ExpertAdvisorInterface
 *
 * @package App\Metatrader\Automation\ExpertAdvisor
 */
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