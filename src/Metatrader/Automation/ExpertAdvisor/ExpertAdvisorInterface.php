<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\ExpertAdvisor;

use App\Metatrader\Automation\Domain\BacktestInterface;

interface ExpertAdvisorInterface
{
    public static function getExpertAdvisorClass(string $expertAdvisorName): string;

    public function getBacktestGenerator(BacktestInterface $backtest): \Generator;

    public function getName(): string;

    public function getParameters(): ExpertAdvisorParameters;

    public function isActive(): bool;
}
