<?php

namespace Metatrader\Automation\Backtest;

use DateTime;
use Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;

/**
 * Class Backtest
 *
 * @package Metatrader\Automation\Backtest
 */
class Backtest implements BacktestInterface
{
    /**
     * @var AbstractExpertAdvisor
     */
    private AbstractExpertAdvisor $expertAdvisor;

    /**
     * @var string
     */
    private string $symbol;

    /**
     * @var string
     */
    private string $period;

    /**
     * @var int
     */
    private int $deposit;

    /**
     * @var DateTime
     */
    private DateTime $fromDate;

    /**
     * @var DateTime
     */
    private DateTime $toDate;

    /**
     * @return AbstractExpertAdvisor
     */
    public function getExpertAdvisor(): AbstractExpertAdvisor
    {
        return $this->expertAdvisor;
    }

    /**
     * @param AbstractExpertAdvisor $expertAdvisor
     */
    public function setExpertAdvisor(AbstractExpertAdvisor $expertAdvisor): void
    {
        $this->expertAdvisor = $expertAdvisor;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getPeriod(): string
    {
        return $this->period;
    }

    /**
     * @param string $period
     */
    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getDeposit(): int
    {
        return $this->deposit;
    }

    /**
     * @param int $deposit
     */
    public function setDeposit(int $deposit): void
    {
        $this->deposit = $deposit;
    }

    /**
     * @return DateTime
     */
    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    /**
     * @param DateTime $fromDate
     */
    public function setFromDate(DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return DateTime
     */
    public function getToDate(): DateTime
    {
        return $this->toDate;
    }

    /**
     * @param DateTime $toDate
     */
    public function setToDate(DateTime $toDate): void
    {
        $this->toDate = $toDate;
    }
}