<?php

namespace App\Metatrader\Automation\Model;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use App\Metatrader\Automation\Validator\Constraints as Validators;

/**
 * Class BacktestModel
 *
 * @package App\Metatrader\Automation\Model
 */
class BacktestModel extends AbstractModel
{
    /**
     * @Assert\NotBlank
     * @Validators\ExpertAdvisor
     *
     * @var string
     */
    private string $name;

    /**
     * @Assert\NotBlank
     *
     * @var string
     */
    private string $symbol;

    /**
     * @Assert\NotBlank
     * @Assert\Choice({"M1", "M5", "M15", "M30", "H1", "H4", "D1", "W1", "MN1"})
     *
     * @var string
     */
    private string $period;

    /**
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(500)
     *
     * @var int
     */
    private int $deposit;

    /**
     * @Assert\NotBlank
     * @Validators\Date("Y-m-d")
     *
     * @var DateTime
     */
    private DateTime $from;

    /**
     * @Assert\NotBlank
     * @Validators\Date("Y-m-d")
     *
     * @var DateTime
     */
    private DateTime $to;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getPeriod(): string
    {
        return $this->period;
    }

    /**
     * @return int
     */
    public function getDeposit(): int
    {
        return $this->deposit;
    }

    /**
     * @return DateTime
     */
    public function getFrom(): DateTime
    {
        return $this->from;
    }

    /**
     * @return DateTime
     */
    public function getTo(): DateTime
    {
        return $this->to;
    }
}