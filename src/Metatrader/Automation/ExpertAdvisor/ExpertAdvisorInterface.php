<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

interface ExpertAdvisorInterface
{
    public static function getExpertAdvisorClass(string $expertAdvisorName): string;

    public function getConfig(): ExpertAdvisorConfig;

    public function getName(): string;
}
