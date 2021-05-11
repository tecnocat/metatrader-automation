<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use App\Metatrader\Automation\Repository\BacktestReportEntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BacktestReportEntityRepository::class)
 */
class BacktestReportEntity extends AbstractBaseEntity
{
    /**
     * @ORM\Column(type="float")
     */
    private float $absoluteDrawdown;

    /**
     * @ORM\Column(type="integer")
     */
    private int $averageConsecutiveLosses;

    /**
     * @ORM\Column(type="integer")
     */
    private int $averageConsecutiveWins;

    /**
     * @ORM\Column(type="float")
     */
    private float $averageLossTrade;

    /**
     * @ORM\Column(type="float")
     */
    private float $averageProfitTrade;

    /**
     * @ORM\Column(type="integer")
     */
    private int $barsInTest;

    /**
     * @ORM\Column(type="float")
     */
    private float $expectedPayoff;

    /**
     * @ORM\Column(type="float")
     */
    private float $grossLoss;

    /**
     * @ORM\Column(type="float")
     */
    private float $grossProfit;

    /**
     * @ORM\Column(type="integer")
     */
    private int $initialDeposit;

    /**
     * @ORM\Column(type="float")
     */
    private float $largestLossTrade;

    /**
     * @ORM\Column(type="float")
     */
    private float $largestProfitTrade;

    /**
     * @ORM\Column(type="integer")
     */
    private int $longPositions;

    /**
     * @ORM\Column(type="float")
     */
    private float $longPositionsWon;

    /**
     * @ORM\Column(type="integer")
     */
    private int $lossTrades;

    /**
     * @ORM\Column(type="float")
     */
    private float $lossTradesPercent;

    /**
     * @ORM\Column(type="float")
     */
    private float $maximalConsecutiveLoss;

    /**
     * @ORM\Column(type="integer")
     */
    private int $maximalConsecutiveLossCount;

    /**
     * @ORM\Column(type="float")
     */
    private float $maximalConsecutiveProfit;

    /**
     * @ORM\Column(type="integer")
     */
    private int $maximalConsecutiveProfitCount;

    /**
     * @ORM\Column(type="float")
     */
    private float $maximalDrawdown;

    /**
     * @ORM\Column(type="integer")
     */
    private int $maximumConsecutiveLosses;

    /**
     * @ORM\Column(type="float")
     */
    private float $maximumConsecutiveLossesMoney;

    /**
     * @ORM\Column(type="integer")
     */
    private int $maximumConsecutiveWins;

    /**
     * @ORM\Column(type="float")
     */
    private float $maximumConsecutiveWinsMoney;

    /**
     * @ORM\Column(type="integer")
     */
    private int $mismatchedChartsErrors;

    /**
     * @ORM\Column(type="string")
     */
    private string $model;

    /**
     * @ORM\Column(type="float")
     */
    private float $modellingQuality;

    /**
     * @ORM\Column(type="array")
     */
    private array $parameters;

    /**
     * @ORM\Column(type="string")
     */
    private string $period;

    /**
     * @ORM\Column(type="float")
     */
    private float $profitFactor;

    /**
     * @ORM\Column(type="integer")
     */
    private int $profitTrades;

    /**
     * @ORM\Column(type="float")
     */
    private float $profitTradesPercent;

    /**
     * @ORM\Column(type="float")
     */
    private float $relativeDrawdown;

    /**
     * @ORM\Column(type="string")
     */
    private string $reportName;

    /**
     * @ORM\Column(type="integer")
     */
    private int $shortPositions;

    /**
     * @ORM\Column(type="float")
     */
    private float $shortPositionsWon;

    /**
     * @ORM\Column(type="integer")
     */
    private int $spread;

    /**
     * @ORM\Column(type="string")
     */
    private string $symbol;

    /**
     * @ORM\Column(type="integer")
     */
    private int $ticksModelled;

    /**
     * @ORM\Column(type="float")
     */
    private float $totalNetProfit;

    /**
     * @ORM\Column(type="integer")
     */
    private int $totalTrades;

    public function getAbsoluteDrawdown(): float
    {
        return $this->absoluteDrawdown;
    }

    public function setAbsoluteDrawdown(float $absoluteDrawdown): void
    {
        $this->absoluteDrawdown = $absoluteDrawdown;
    }

    public function getAverageConsecutiveLosses(): int
    {
        return $this->averageConsecutiveLosses;
    }

    public function setAverageConsecutiveLosses(int $averageConsecutiveLosses): void
    {
        $this->averageConsecutiveLosses = $averageConsecutiveLosses;
    }

    public function getAverageConsecutiveWins(): int
    {
        return $this->averageConsecutiveWins;
    }

    public function setAverageConsecutiveWins(int $averageConsecutiveWins): void
    {
        $this->averageConsecutiveWins = $averageConsecutiveWins;
    }

    public function getAverageLossTrade(): float
    {
        return $this->averageLossTrade;
    }

    public function setAverageLossTrade(float $averageLossTrade): void
    {
        $this->averageLossTrade = $averageLossTrade;
    }

    public function getAverageProfitTrade(): float
    {
        return $this->averageProfitTrade;
    }

    public function setAverageProfitTrade(float $averageProfitTrade): void
    {
        $this->averageProfitTrade = $averageProfitTrade;
    }

    public function getBarsInTest(): int
    {
        return $this->barsInTest;
    }

    public function setBarsInTest(int $barsInTest): void
    {
        $this->barsInTest = $barsInTest;
    }

    public function getExpectedPayoff(): float
    {
        return $this->expectedPayoff;
    }

    public function setExpectedPayoff(float $expectedPayoff): void
    {
        $this->expectedPayoff = $expectedPayoff;
    }

    public function getGrossLoss(): float
    {
        return $this->grossLoss;
    }

    public function setGrossLoss(float $grossLoss): void
    {
        $this->grossLoss = $grossLoss;
    }

    public function getGrossProfit(): float
    {
        return $this->grossProfit;
    }

    public function setGrossProfit(float $grossProfit): void
    {
        $this->grossProfit = $grossProfit;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInitialDeposit(): int
    {
        return $this->initialDeposit;
    }

    public function setInitialDeposit(int $initialDeposit): void
    {
        $this->initialDeposit = $initialDeposit;
    }

    public function getLargestLossTrade(): float
    {
        return $this->largestLossTrade;
    }

    public function setLargestLossTrade(float $largestLossTrade): void
    {
        $this->largestLossTrade = $largestLossTrade;
    }

    public function getLargestProfitTrade(): float
    {
        return $this->largestProfitTrade;
    }

    public function setLargestProfitTrade(float $largestProfitTrade): void
    {
        $this->largestProfitTrade = $largestProfitTrade;
    }

    public function getLongPositions(): int
    {
        return $this->longPositions;
    }

    public function setLongPositions(int $longPositions): void
    {
        $this->longPositions = $longPositions;
    }

    public function getLongPositionsWon(): float
    {
        return $this->longPositionsWon;
    }

    public function setLongPositionsWon(float $longPositionsWon): void
    {
        $this->longPositionsWon = $longPositionsWon;
    }

    public function getLossTrades(): int
    {
        return $this->lossTrades;
    }

    public function setLossTrades(int $lossTrades): void
    {
        $this->lossTrades = $lossTrades;
    }

    public function getLossTradesPercent(): float
    {
        return $this->lossTradesPercent;
    }

    public function setLossTradesPercent(float $lossTradesPercent): void
    {
        $this->lossTradesPercent = $lossTradesPercent;
    }

    public function getMaximalConsecutiveLoss(): float
    {
        return $this->maximalConsecutiveLoss;
    }

    public function setMaximalConsecutiveLoss(float $maximalConsecutiveLoss): void
    {
        $this->maximalConsecutiveLoss = $maximalConsecutiveLoss;
    }

    public function getMaximalConsecutiveLossCount(): int
    {
        return $this->maximalConsecutiveLossCount;
    }

    public function setMaximalConsecutiveLossCount(int $maximalConsecutiveLossCount): void
    {
        $this->maximalConsecutiveLossCount = $maximalConsecutiveLossCount;
    }

    public function getMaximalConsecutiveProfit(): float
    {
        return $this->maximalConsecutiveProfit;
    }

    public function setMaximalConsecutiveProfit(float $maximalConsecutiveProfit): void
    {
        $this->maximalConsecutiveProfit = $maximalConsecutiveProfit;
    }

    public function getMaximalConsecutiveProfitCount(): int
    {
        return $this->maximalConsecutiveProfitCount;
    }

    public function setMaximalConsecutiveProfitCount(int $maximalConsecutiveProfitCount): void
    {
        $this->maximalConsecutiveProfitCount = $maximalConsecutiveProfitCount;
    }

    public function getMaximalDrawdown(): float
    {
        return $this->maximalDrawdown;
    }

    public function setMaximalDrawdown(float $maximalDrawdown): void
    {
        $this->maximalDrawdown = $maximalDrawdown;
    }

    public function getMaximumConsecutiveLosses(): int
    {
        return $this->maximumConsecutiveLosses;
    }

    public function setMaximumConsecutiveLosses(int $maximumConsecutiveLosses): void
    {
        $this->maximumConsecutiveLosses = $maximumConsecutiveLosses;
    }

    public function getMaximumConsecutiveLossesMoney(): float
    {
        return $this->maximumConsecutiveLossesMoney;
    }

    public function setMaximumConsecutiveLossesMoney(float $maximumConsecutiveLossesMoney): void
    {
        $this->maximumConsecutiveLossesMoney = $maximumConsecutiveLossesMoney;
    }

    public function getMaximumConsecutiveWins(): int
    {
        return $this->maximumConsecutiveWins;
    }

    public function setMaximumConsecutiveWins(int $maximumConsecutiveWins): void
    {
        $this->maximumConsecutiveWins = $maximumConsecutiveWins;
    }

    public function getMaximumConsecutiveWinsMoney(): float
    {
        return $this->maximumConsecutiveWinsMoney;
    }

    public function setMaximumConsecutiveWinsMoney(float $maximumConsecutiveWinsMoney): void
    {
        $this->maximumConsecutiveWinsMoney = $maximumConsecutiveWinsMoney;
    }

    public function getMismatchedChartsErrors(): int
    {
        return $this->mismatchedChartsErrors;
    }

    public function setMismatchedChartsErrors(int $mismatchedChartsErrors): void
    {
        $this->mismatchedChartsErrors = $mismatchedChartsErrors;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getModellingQuality(): float
    {
        return $this->modellingQuality;
    }

    public function setModellingQuality(float $modellingQuality): void
    {
        $this->modellingQuality = $modellingQuality;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getProfitFactor(): float
    {
        return $this->profitFactor;
    }

    public function setProfitFactor(float $profitFactor): void
    {
        $this->profitFactor = $profitFactor;
    }

    public function getProfitTrades(): int
    {
        return $this->profitTrades;
    }

    public function setProfitTrades(int $profitTrades): void
    {
        $this->profitTrades = $profitTrades;
    }

    public function getProfitTradesPercent(): float
    {
        return $this->profitTradesPercent;
    }

    public function setProfitTradesPercent(float $profitTradesPercent): void
    {
        $this->profitTradesPercent = $profitTradesPercent;
    }

    public function getRelativeDrawdown(): float
    {
        return $this->relativeDrawdown;
    }

    public function setRelativeDrawdown(float $relativeDrawdown): void
    {
        $this->relativeDrawdown = $relativeDrawdown;
    }

    public function getReportName(): string
    {
        return $this->reportName;
    }

    public function setReportName(string $reportName): void
    {
        $this->reportName = $reportName;
    }

    public function getShortPositions(): int
    {
        return $this->shortPositions;
    }

    public function setShortPositions(int $shortPositions): void
    {
        $this->shortPositions = $shortPositions;
    }

    public function getShortPositionsWon(): float
    {
        return $this->shortPositionsWon;
    }

    public function setShortPositionsWon(float $shortPositionsWon): void
    {
        $this->shortPositionsWon = $shortPositionsWon;
    }

    public function getSpread(): int
    {
        return $this->spread;
    }

    public function setSpread(int $spread): void
    {
        $this->spread = $spread;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getTicksModelled(): int
    {
        return $this->ticksModelled;
    }

    public function setTicksModelled(int $ticksModelled): void
    {
        $this->ticksModelled = $ticksModelled;
    }

    public function getTotalNetProfit(): float
    {
        return $this->totalNetProfit;
    }

    public function setTotalNetProfit(float $totalNetProfit): void
    {
        $this->totalNetProfit = $totalNetProfit;
    }

    public function getTotalTrades(): int
    {
        return $this->totalTrades;
    }

    public function setTotalTrades(int $totalTrades): void
    {
        $this->totalTrades = $totalTrades;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
