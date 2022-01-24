<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

use App\Metatrader\Automation\DTO\BacktestDTO;
use App\Metatrader\Automation\DTO\BacktestExecutionDTO;
use Symfony\Component\HttpFoundation\ParameterBag;

interface ExpertAdvisorInterface
{
    public static function getExpertAdvisorClass(string $expertAdvisorName): string;

    public function getBacktestExecutionDTO(BacktestDTO $backtestDTO, array $iteration): BacktestExecutionDTO;

    public function getIteration(BacktestDTO $backtestDTO): \Generator;

    public function getName(): string;

    public function getParameters(): ParameterBag;

    public function isActive(): bool;
}
