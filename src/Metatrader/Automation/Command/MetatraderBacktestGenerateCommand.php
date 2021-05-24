<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\Metatrader\ExecutionEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

class MetatraderBacktestGenerateCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('expertAdvisor', InputArgument::REQUIRED, 'The name of the Expert Advisor')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The symbol to test with the Expert Advisor')
            ->addArgument('period', InputArgument::REQUIRED, 'The period to test with the Expert Advisor')
            ->addArgument('deposit', InputArgument::REQUIRED, 'The amount of equity to test with the Expert Advisor')
            ->addArgument('from', InputArgument::REQUIRED, 'The from date to test with the Expert Advisor')
            ->addArgument('to', InputArgument::REQUIRED, 'The to date to test with the Expert Advisor')
            ->setDescription('Generate a Metatrader backtest reports based on selected parameters')
            ->setHelp('This command allow you to run multiple Metatrader instances and backtests automatically')
            ->setName($this->generateName())
        ;
    }

    public function process(): int
    {
        $parameters       = array_merge($this->getArguments(), $this->getOptions());
        $buildEntityEvent = new BuildEntityEvent(BacktestEntity::class, $parameters);
        $this->dispatch($buildEntityEvent);

        if ($this->hasErrors($buildEntityEvent))
        {
            return Command::FAILURE;
        }

        /** @var BacktestEntity $entity */
        $entity  = $buildEntityEvent->getEntity();
        $headers = ['Expert Advisor', 'Symbol', 'Period', 'Deposit', 'From', 'To'];
        $rows    = [
            [
                $entity->getExpertAdvisor(),
                $entity->getSymbol(),
                $entity->getPeriod(),
                $entity->getDeposit(),
                $entity->getFrom()->format('Y-m-d'),
                $entity->getTo()->format('Y-m-d'),
            ],
        ];
        $this->comment('Executing Metatrader Automation...');
        $this->table($headers, $rows);

        $executionEvent = new ExecutionEvent($entity);
        $this->dispatch($executionEvent);

        if ($this->hasErrors($executionEvent))
        {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
