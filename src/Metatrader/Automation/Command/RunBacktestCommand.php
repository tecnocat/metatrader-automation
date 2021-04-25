<?php

namespace Metatrader\Automation\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunBacktestCommand extends Command
{
    protected static $defaultName = "run-backtest";

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Run a Metatrader backtest');
        $this->setHelp('This command allow you to run multiple Metatrader instances and backtests automatically');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}