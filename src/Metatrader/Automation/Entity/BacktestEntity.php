<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use App\Metatrader\Automation\Repository\BacktestEntityRepository;
use App\Metatrader\Automation\Validator\Constraints as Validators;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BacktestEntityRepository::class)
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"name"})})
 */
class BacktestEntity extends AbstractEntity
{
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
     * @Assert\GreaterThanOrEqual(500)
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private int $initialDeposit;

    /**
     * @Assert\Regex("/^[A-Z]{1}[a-z]+:\.?\w+:(M|H|D|W|MN)(1|4|5|15|30):\d{4}-\d{2}-\d{2}:\d{4}-\d{2}-\d{2}:\d+:.*$/")
     * @ORM\Column(type="string")
     */
    private string $lastBacktestReportName;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^[A-Z]{1}[a-z]+:\.?\w+:(M|H|D|W|MN)(1|4|5|15|30):\d{4}-\d{2}-\d{2}:\d{4}-\d{2}-\d{2}:\d+$/")
     * @ORM\Column(type="string", length=64)
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
     * @ORM\Column(type="string")
     */
    private string $symbol;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime", name="`to`")
     * @Validators\Date("Y-m-d")
     */
    private \DateTime $to;

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

    public function getInitialDeposit(): int
    {
        return $this->initialDeposit;
    }

    public function setInitialDeposit(int $initialDeposit): void
    {
        $this->initialDeposit = $initialDeposit;
    }

    public function getLastBacktestReportName(): string
    {
        return $this->lastBacktestReportName;
    }

    public function setLastBacktestReportName(string $lastBacktestReportName): void
    {
        $this->lastBacktestReportName = $lastBacktestReportName;
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

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }

    public function getTo(): \DateTime
    {
        return $this->to;
    }

    public function setTo(\DateTime $to): void
    {
        $this->to = $to;
    }
}
