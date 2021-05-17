<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Entity\BacktestEntity;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\MetatraderExecutionEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function process(InputInterface $input, OutputInterface $output): int
    {
        $parameters       = array_merge($input->getArguments(), $input->getOptions());
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

        $metatraderExecutionEvent = new MetatraderExecutionEvent($entity);
        $this->dispatch($metatraderExecutionEvent);

        if ($this->hasErrors($metatraderExecutionEvent))
        {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
