<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use App\Metatrader\Automation\Repository\BacktestReportEntityRepository;
use App\Metatrader\Automation\Validator\Constraints as Validators;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BacktestReportEntityRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 */
class BacktestReportEntity extends AbstractEntity
{
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $absoluteDrawdown;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $averageConsecutiveLosses;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $averageConsecutiveWins;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $averageLossTrade;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $averageProfitTrade;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $barsInTest;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $expectedPayoff;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     * @Validators\ExpertAdvisor
     */
    private string $expertAdvisorName;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime", name="`from`")
     * @Validators\Date("Y-m-d")
     */
    private \DateTime $from;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $grossLoss;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $grossProfit;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $initialDeposit;
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="array")
     */
    private array $inputs;
    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $largestLossTrade;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $largestProfitTrade;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $longPositions;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $lossTrades;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $maximalConsecutiveLoss;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $maximalConsecutiveProfit;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $maximalDrawdown;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $maximumConsecutiveLosses;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $maximumConsecutiveWins;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $mismatchedChartsErrors;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private string $model;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $modellingQuality;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @Assert\Choice({"M1", "M5", "M15", "M30", "H1", "H4", "D1", "W1", "MN1"})
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private string $period;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $profitFactor;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $profitTrades;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $relativeDrawdown;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $shortPositions;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $spread;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private string $symbol;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $ticksModelled;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime", name="`to`")
     * @Validators\Date("Y-m-d")
     */
    private \DateTime $to;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="float")
     */
    private float $totalNetProfit;

    /**
     * @Assert\NotBlank
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

    public function getExpertAdvisorName(): string
    {
        return $this->expertAdvisorName;
    }

    public function setExpertAdvisorName(string $expertAdvisorName): void
    {
        $this->expertAdvisorName = $expertAdvisorName;
    }

    public function getFrom(): \DateTime
    {
        return $this->from;
    }

    public function setFrom(\DateTime $from): void
    {
        $this->from = $from;
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

    public function getInitialDeposit(): int
    {
        return $this->initialDeposit;
    }

    public function setInitialDeposit(int $initialDeposit): void
    {
        $this->initialDeposit = $initialDeposit;
    }

    public function getInputs(): array
    {
        return $this->inputs;
    }

    public function setInputs(array $inputs): void
    {
        $this->inputs = $inputs;
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

    public function getLossTrades(): int
    {
        return $this->lossTrades;
    }

    public function setLossTrades(int $lossTrades): void
    {
        $this->lossTrades = $lossTrades;
    }

    public function getMaximalConsecutiveLoss(): float
    {
        return $this->maximalConsecutiveLoss;
    }

    public function setMaximalConsecutiveLoss(float $maximalConsecutiveLoss): void
    {
        $this->maximalConsecutiveLoss = $maximalConsecutiveLoss;
    }

    public function getMaximalConsecutiveProfit(): float
    {
        return $this->maximalConsecutiveProfit;
    }

    public function setMaximalConsecutiveProfit(float $maximalConsecutiveProfit): void
    {
        $this->maximalConsecutiveProfit = $maximalConsecutiveProfit;
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

    public function getMaximumConsecutiveWins(): int
    {
        return $this->maximumConsecutiveWins;
    }

    public function setMaximumConsecutiveWins(int $maximumConsecutiveWins): void
    {
        $this->maximumConsecutiveWins = $maximumConsecutiveWins;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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

    public function getRelativeDrawdown(): float
    {
        return $this->relativeDrawdown;
    }

    public function setRelativeDrawdown(float $relativeDrawdown): void
    {
        $this->relativeDrawdown = $relativeDrawdown;
    }

    public function getShortPositions(): int
    {
        return $this->shortPositions;
    }

    public function setShortPositions(int $shortPositions): void
    {
        $this->shortPositions = $shortPositions;
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

    public function getTo(): \DateTime
    {
        return $this->to;
    }

    public function setTo(\DateTime $to): void
    {
        $this->to = $to;
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
}
