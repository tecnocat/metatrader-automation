<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\DTO\BacktestDTO;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class MetatraderBacktestGenerateCommand extends AbstractCommand
{
    public function process(): int
    {
        $backtestDTO = new BacktestDTO(array_merge($this->getArguments(), $this->getOptions()));
        $headers     = ['Expert Advisor Name', 'Symbol', 'Period', 'From', 'To', 'Initial Deposit'];
        $rows        = [
            [
                $backtestDTO->expertAdvisorName,
                $backtestDTO->symbol,
                $backtestDTO->period,
                $backtestDTO->from->format('Y-m-d'),
                $backtestDTO->to->format('Y-m-d'),
                $backtestDTO->initialDeposit,
            ],
        ];
        $this->comment('Executing Metatrader Automation...');
        $this->table($headers, $rows);

        $executionEvent = new ExecutionEvent($backtestDTO);
        $this->dispatch($executionEvent);

        if ($this->hasErrors($executionEvent))
        {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('expertAdvisorName', InputArgument::REQUIRED, 'The name of the Expert Advisor')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The symbol to test with the Expert Advisor')
            ->addArgument('period', InputArgument::REQUIRED, 'The period to test with the Expert Advisor')
            ->addArgument('initialDeposit', InputArgument::REQUIRED, 'The amount of equity to test with the Expert Advisor')
            ->addArgument('from', InputArgument::REQUIRED, 'The from date to test with the Expert Advisor')
            ->addArgument('to', InputArgument::REQUIRED, 'The to date to test with the Expert Advisor')
            ->setDescription('Generate a Metatrader backtest reports based on selected parameters')
            ->setHelp('This command allow you to run multiple Metatrader instances and backtests automatically')
            ->setName($this->generateName())
        ;
    }
}
