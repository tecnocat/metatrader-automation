<?php

namespace Metatrader\Automation\Backtest;

use DateTime;
use Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;

/**
 * Interface BacktestInterface
 *
 * @package Metatrader\Automation\Backtest
 */
interface BacktestInterface
{
    /**
     * @param AbstractExpertAdvisor $expertAdvisor
     */
    public function setExpertAdvisor(AbstractExpertAdvisor $expertAdvisor): void;

    /**
     * @return AbstractExpertAdvisor
     */
    public function getExpertAdvisor(): AbstractExpertAdvisor;

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void;

    /**
     * @return string
     */
    public function getSymbol(): string;

    /**
     * @param string $period
     */
    public function setPeriod(string $period): void;

    /**
     * @return string
     */
    public function getPeriod(): string;

    /**
     * @param int $deposit
     */
    public function setDeposit(int $deposit): void;

    /**
     * @return int
     */
    public function getDeposit(): int;

    /**
     * @param DateTime $fromDate
     */
    public function setFromDate(DateTime $fromDate): void;

    /**
     * @return DateTime
     */
    public function getFromDate(): DateTime;

    /**
     * @param DateTime $toDate
     */
    public function setToDate(DateTime $toDate): void;

    /**
     * @return DateTime
     */
    public function getToDate(): DateTime;
}