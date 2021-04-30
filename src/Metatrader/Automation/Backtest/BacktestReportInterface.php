<?php

namespace App\Metatrader\Automation\Backtest;

/**
 * Interface BacktestReportInterface
 *
 * @package App\Metatrader\Automation\Backtest
 */
interface BacktestReportInterface
{
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
     * @param string $model
     */
    public function setModel(string $model): void;

    /**
     * @return string
     */
    public function getModel(): string;

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void;

    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param int $barsInTest
     */
    public function setBarsInTest(int $barsInTest): void;

    /**
     * @return int
     */
    public function getBarsInTest(): int;

    /**
     * @param int $ticksModelled
     */
    public function setTicksModelled(int $ticksModelled): void;

    /**
     * @return int
     */
    public function getTicksModelled(): int;

    /**
     * @param float $modellingQuality
     */
    public function setModellingQuality(float $modellingQuality): void;

    /**
     * @return float
     */
    public function getModellingQuality(): float;

    /**
     * @param int $mismatchedChartsErrors
     */
    public function setMismatchedChartsErrors(int $mismatchedChartsErrors): void;

    /**
     * @return int
     */
    public function getMismatchedChartsErrors(): int;

    /**
     * @param int $initialDeposit
     */
    public function setInitialDeposit(int $initialDeposit): void;

    /**
     * @return int
     */
    public function getInitialDeposit(): int;

    /**
     * @param int $spread
     */
    public function setSpread(int $spread): void;

    /**
     * @return int
     */
    public function getSpread(): int;

    /**
     * @param float $totalNetProfit
     */
    public function setTotalNetProfit(float $totalNetProfit): void;

    /**
     * @return float
     */
    public function getTotalNetProfit(): float;

    /**
     * @param float $grossProfit
     */
    public function setGrossProfit(float $grossProfit): void;

    /**
     * @return float
     */
    public function getGrossProfit(): float;

    /**
     * @param float $grossLoss
     */
    public function setGrossLoss(float $grossLoss): void;

    /**
     * @return float
     */
    public function getGrossLoss(): float;

    /**
     * @param float $profitFactor
     */
    public function setProfitFactor(float $profitFactor): void;

    /**
     * @return float
     */
    public function getProfitFactor(): float;

    /**
     * @param float $expectedPayoff
     */
    public function setExpectedPayoff(float $expectedPayoff): void;

    /**
     * @return float
     */
    public function getExpectedPayoff(): float;

    /**
     * @param float $absoluteDrawdown
     */
    public function setAbsoluteDrawdown(float $absoluteDrawdown): void;

    /**
     * @return float
     */
    public function getAbsoluteDrawdown(): float;

    /**
     * @param float $maximalDrawdown
     */
    public function setMaximalDrawdown(float $maximalDrawdown): void;

    /**
     * @return float
     */
    public function getMaximalDrawdown(): float;

    /**
     * @param float $relativeDrawdown
     */
    public function setRelativeDrawdown(float $relativeDrawdown): void;

    /**
     * @return float
     */
    public function getRelativeDrawdown(): float;

    /**
     * @param int $totalTrades
     */
    public function setTotalTrades(int $totalTrades): void;

    /**
     * @return int
     */
    public function getTotalTrades(): int;

    /**
     * @param int $shortPositions
     */
    public function setShortPositions(int $shortPositions): void;

    /**
     * @return int
     */
    public function getShortPositions(): int;

    /**
     * @param float $shortPositionsWon
     */
    public function setShortPositionsWon(float $shortPositionsWon): void;

    /**
     * @return float
     */
    public function getShortPositionsWon(): float;

    /**
     * @param int $longPositions
     */
    public function setLongPositions(int $longPositions): void;

    /**
     * @return int
     */
    public function getLongPositions(): int;

    /**
     * @param float $longPositions
     */
    public function setLongPositionsWon(float $longPositions): void;

    /**
     * @return float
     */
    public function getLongPositionsWon(): float;

    /**
     * @param int $profitTrades
     */
    public function setProfitTrades(int $profitTrades): void;

    /**
     * @return int
     */
    public function getProfitTrades(): int;

    /**
     * @param float $profitTradesPercent
     */
    public function setProfitTradesPercent(float $profitTradesPercent): void;

    /**
     * @return float
     */
    public function getProfitTradesPercent(): float;

    /**
     * @param int $lossTrades
     */
    public function setLossTrades(int $lossTrades): void;

    /**
     * @return int
     */
    public function getLossTrades(): int;

    /**
     * @param float $lossTradesPercent
     */
    public function setLossTradesPercent(float $lossTradesPercent): void;

    /**
     * @return float
     */
    public function getLossTradesPercent(): float;

    /**
     * @param float $largestProfitTrade
     */
    public function setLargestProfitTrade(float $largestProfitTrade): void;

    /**
     * @return float
     */
    public function getLargestProfitTrade(): float;

    /**
     * @param float $largestLossTrade
     */
    public function setLargestLossTrade(float $largestLossTrade): void;

    /**
     * @return float
     */
    public function getLargestLossTrade(): float;

    /**
     * @param float $averageProfitTrade
     */
    public function setAverageProfitTrade(float $averageProfitTrade): void;

    /**
     * @return float
     */
    public function getAverageProfitTrade(): float;

    /**
     * @param float $averageLossTrade
     */
    public function setAverageLossTrade(float $averageLossTrade): void;

    /**
     * @return float
     */
    public function getAverageLossTrade(): float;

    /**
     * @param int $maximumConsecutiveWins
     */
    public function setMaximumConsecutiveWins(int $maximumConsecutiveWins): void;

    /**
     * @return int
     */
    public function getMaximumConsecutiveWins(): int;

    /**
     * @param float $maximumConsecutiveWinsMoney
     */
    public function setMaximumConsecutiveWinsMoney(float $maximumConsecutiveWinsMoney): void;

    /**
     * @return float
     */
    public function getMaximumConsecutiveWinsMoney(): float;

    /**
     * @param int $maximumConsecutiveLosses
     */
    public function setMaximumConsecutiveLosses(int $maximumConsecutiveLosses): void;

    /**
     * @return int
     */
    public function getMaximumConsecutiveLosses(): int;

    /**
     * @param float $maximumConsecutiveLossesMoney
     */
    public function setMaximumConsecutiveLossesMoney(float $maximumConsecutiveLossesMoney): void;

    /**
     * @return float
     */
    public function getMaximumConsecutiveLossesMoney(): float;

    /**
     * @param float $maximalConsecutiveProfit
     */
    public function setMaximalConsecutiveProfit(float $maximalConsecutiveProfit): void;

    /**
     * @return float
     */
    public function getMaximalConsecutiveProfit(): float;

    /**
     * @param int $maximalConsecutiveProfitCount
     */
    public function setMaximalConsecutiveProfitCount(int $maximalConsecutiveProfitCount): void;

    /**
     * @return int
     */
    public function getMaximalConsecutiveProfitCount(): int;

    /**
     * @param float $maximalConsecutiveLoss
     */
    public function setMaximalConsecutiveLoss(float $maximalConsecutiveLoss): void;

    /**
     * @return float
     */
    public function getMaximalConsecutiveLoss(): float;

    /**
     * @param int $maximalConsecutiveLossCount
     */
    public function setMaximalConsecutiveLossCount(int $maximalConsecutiveLossCount): void;

    /**
     * @return int
     */
    public function getMaximalConsecutiveLossCount(): int;

    /**
     * @param int $averageConsecutiveWins
     */
    public function setAverageConsecutiveWins(int $averageConsecutiveWins): void;

    /**
     * @return int
     */
    public function getAverageConsecutiveWins(): int;

    /**
     * @param int $averageConsecutiveLosses
     */
    public function setAverageConsecutiveLosses(int $averageConsecutiveLosses): void;

    /**
     * @return int
     */
    public function getAverageConsecutiveLosses(): int;
}