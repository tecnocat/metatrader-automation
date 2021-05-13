<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Entity;

use App\Metatrader\Automation\Repository\BacktestEntityRepository;
use App\Metatrader\Automation\Validator\Constraints as Validators;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BacktestEntityRepository::class)
 */
class BacktestEntity extends AbstractEntity
{
    /**
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(500)
     * @ORM\Column(type="integer")
     */
    private int $deposit;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     * @Validators\Date("Y-m-d")
     */
    private \DateTime $from;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     * @Validators\ExpertAdvisor
     */
    private string $name;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     * @Assert\Choice({"M1", "M5", "M15", "M30", "H1", "H4", "D1", "W1", "MN1"})
     */
    private string $period;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private string $symbol;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     * @Validators\Date("Y-m-d")
     */
    private \DateTime $to;

    public function getDeposit(): int
    {
        return $this->deposit;
    }

    public function setDeposit(int $deposit): void
    {
        $this->deposit = $deposit;
    }

    public function getFrom(): \DateTime
    {
        return $this->from;
    }

    public function setFrom(\DateTime $from): void
    {
        $this->from = $from;
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
