<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Domain;

use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;

interface BacktestInterface
{
    public function getDeposit(): int;

    public function getExpertAdvisor(): AbstractExpertAdvisor;

    public function getFrom(): \DateTime;

    public function getPeriod(): string;

    public function getSymbol(): string;

    public function getTo(): \DateTime;

    public function setDeposit(int $deposit): void;

    public function setExpertAdvisor(AbstractExpertAdvisor $expertAdvisor): void;

    public function setFrom(\DateTime $from): void;

    public function setPeriod(string $period): void;

    public function setSymbol(string $symbol): void;

    public function setTo(\DateTime $to): void;
}
