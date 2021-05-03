<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Domain;

interface BacktestReportInterface
{
    public function getAbsoluteDrawdown(): float;

    public function getAverageConsecutiveLosses(): int;

    public function getAverageConsecutiveWins(): int;

    public function getAverageLossTrade(): float;

    public function getAverageProfitTrade(): float;

    public function getBarsInTest(): int;

    public function getExpectedPayoff(): float;

    public function getGrossLoss(): float;

    public function getGrossProfit(): float;

    public function getInitialDeposit(): int;

    public function getLargestLossTrade(): float;

    public function getLargestProfitTrade(): float;

    public function getLongPositions(): int;

    public function getLongPositionsWon(): float;

    public function getLossTrades(): int;

    public function getLossTradesPercent(): float;

    public function getMaximalConsecutiveLoss(): float;

    public function getMaximalConsecutiveLossCount(): int;

    public function getMaximalConsecutiveProfit(): float;

    public function getMaximalConsecutiveProfitCount(): int;

    public function getMaximalDrawdown(): float;

    public function getMaximumConsecutiveLosses(): int;

    public function getMaximumConsecutiveLossesMoney(): float;

    public function getMaximumConsecutiveWins(): int;

    public function getMaximumConsecutiveWinsMoney(): float;

    public function getMismatchedChartsErrors(): int;

    public function getModel(): string;

    public function getModellingQuality(): float;

    public function getParameters(): array;

    public function getPeriod(): string;

    public function getProfitFactor(): float;

    public function getProfitTrades(): int;

    public function getProfitTradesPercent(): float;

    public function getRelativeDrawdown(): float;

    public function getShortPositions(): int;

    public function getShortPositionsWon(): float;

    public function getSpread(): int;

    public function getSymbol(): string;

    public function getTicksModelled(): int;

    public function getTotalNetProfit(): float;

    public function getTotalTrades(): int;

    public function setAbsoluteDrawdown(float $absoluteDrawdown): void;

    public function setAverageConsecutiveLosses(int $averageConsecutiveLosses): void;

    public function setAverageConsecutiveWins(int $averageConsecutiveWins): void;

    public function setAverageLossTrade(float $averageLossTrade): void;

    public function setAverageProfitTrade(float $averageProfitTrade): void;

    public function setBarsInTest(int $barsInTest): void;

    public function setExpectedPayoff(float $expectedPayoff): void;

    public function setGrossLoss(float $grossLoss): void;

    public function setGrossProfit(float $grossProfit): void;

    public function setInitialDeposit(int $initialDeposit): void;

    public function setLargestLossTrade(float $largestLossTrade): void;

    public function setLargestProfitTrade(float $largestProfitTrade): void;

    public function setLongPositions(int $longPositions): void;

    public function setLongPositionsWon(float $longPositions): void;

    public function setLossTrades(int $lossTrades): void;

    public function setLossTradesPercent(float $lossTradesPercent): void;

    public function setMaximalConsecutiveLoss(float $maximalConsecutiveLoss): void;

    public function setMaximalConsecutiveLossCount(int $maximalConsecutiveLossCount): void;

    public function setMaximalConsecutiveProfit(float $maximalConsecutiveProfit): void;

    public function setMaximalConsecutiveProfitCount(int $maximalConsecutiveProfitCount): void;

    public function setMaximalDrawdown(float $maximalDrawdown): void;

    public function setMaximumConsecutiveLosses(int $maximumConsecutiveLosses): void;

    public function setMaximumConsecutiveLossesMoney(float $maximumConsecutiveLossesMoney): void;

    public function setMaximumConsecutiveWins(int $maximumConsecutiveWins): void;

    public function setMaximumConsecutiveWinsMoney(float $maximumConsecutiveWinsMoney): void;

    public function setMismatchedChartsErrors(int $mismatchedChartsErrors): void;

    public function setModel(string $model): void;

    public function setModellingQuality(float $modellingQuality): void;

    public function setParameters(array $parameters): void;

    public function setPeriod(string $period): void;

    public function setProfitFactor(float $profitFactor): void;

    public function setProfitTrades(int $profitTrades): void;

    public function setProfitTradesPercent(float $profitTradesPercent): void;

    public function setRelativeDrawdown(float $relativeDrawdown): void;

    public function setShortPositions(int $shortPositions): void;

    public function setShortPositionsWon(float $shortPositionsWon): void;

    public function setSpread(int $spread): void;

    public function setSymbol(string $symbol): void;

    public function setTicksModelled(int $ticksModelled): void;

    public function setTotalNetProfit(float $totalNetProfit): void;

    public function setTotalTrades(int $totalTrades): void;
}
