<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

use Symfony\Component\HttpFoundation\ParameterBag;

interface ExpertAdvisorInterface
{
    public static function getExpertAdvisorClass(string $expertAdvisorName): string;

    public function generateBacktestReportName(EntityInterface $backtestEntity): \Generator;

    public function getCurrentBacktestSettings(): array;

    public function getName(): string;

    public function getParameters(): ParameterBag;

    public function isActive(): bool;

    public function setCurrentBacktestSettings(array $currentBacktestSettings): void;

    public function getAlias(): array;
}
