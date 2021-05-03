<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Domain;

use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use DateTime;

class Backtest implements BacktestInterface
{
    private int                   $deposit;
    private AbstractExpertAdvisor $expertAdvisor;
    private DateTime              $from;
    private string                $period;
    private string                $symbol;
    private DateTime              $to;

    public function getDeposit(): int
    {
        return $this->deposit;
    }

    public function setDeposit(int $deposit): void
    {
        $this->deposit = $deposit;
    }

    public function getExpertAdvisor(): AbstractExpertAdvisor
    {
        return $this->expertAdvisor;
    }

    public function setExpertAdvisor(AbstractExpertAdvisor $expertAdvisor): void
    {
        $this->expertAdvisor = $expertAdvisor;
    }

    public function getFrom(): DateTime
    {
        return $this->from;
    }

    public function setFrom(DateTime $from): void
    {
        $this->from = $from;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getTo(): DateTime
    {
        return $this->to;
    }

    public function setTo(DateTime $to): void
    {
        $this->to = $to;
    }
}
