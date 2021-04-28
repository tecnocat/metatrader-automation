<?php

namespace Metatrader\Automation\Command;

use DateTime;
use Exception;
use Metatrader\Automation\Backtest\Backtest;
use Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Metatrader\Automation\ExpertAdvisor\ExpertAdvisorConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class RunBacktestCommand
 *
 * @package Metatrader\Automation\Command
 */
class RunBacktestCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'run-backtest';

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    protected function configure()
    {
        parent::configure();

        $this
            ->setDescription('Run a Metatrader backtest')
            ->setHelp('This command allow you to run multiple Metatrader instances and backtests automatically')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Expert Advisor')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The symbol to test with the Expert Advisor')
            ->addArgument('period', InputArgument::REQUIRED, 'The period to test with the Expert Advisor')
            ->addArgument('deposit', InputArgument::REQUIRED, 'The amount of equity to test with the Expert Advisor')
            ->addArgument('from', InputArgument::REQUIRED, 'The from date to test with the Expert Advisor')
            ->addArgument('to', InputArgument::REQUIRED, 'The to date to test with the Expert Advisor')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setInputOutputStyle($input, $output);

        // TODO: Use input event
        $name    = $input->getArgument('name');
        $symbol  = $input->getArgument('symbol');
        $period  = $input->getArgument('period');
        $deposit = $input->getArgument('deposit');
        $from    = $input->getArgument('from');
        $to      = $input->getArgument('to');

        // TODO: Use validator service
        $this->validateName($name);
        $this->validatePeriod($period);
        $this->validateDeposit($deposit);
        $this->validateDates($from, $to);

        $headers = ['Name', 'Symbol', 'Period', 'Deposit', 'From', 'To'];
        $rows    = [[$name, $symbol, $period, $deposit, $from, $to]];
        $this->io->comment('Executing Metatrader Automation...');
        $this->io->table($headers, $rows);

        // TODO: Use construct event
        $expertAdvisor = $this->getExpertAdvisorInstance($name);
        $backtest      = new Backtest();
        $backtest->setExpertAdvisor($expertAdvisor);
        $backtest->setSymbol($symbol);
        $backtest->setPeriod($period);
        $backtest->setDeposit($deposit);
        $backtest->setFromDate(DateTime::createFromFormat('Y-m-d', $from)->modify('midnight'));
        $backtest->setToDate(DateTime::createFromFormat('Y-m-d', $to)->modify('midnight'));

        // TODO: Event loop between dates

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    private function setInputOutputStyle(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @param string $name
     */
    private function validateName(string $name): void
    {
        if (!class_exists(AbstractExpertAdvisor::getExpertAdvisorClass($name)))
        {
            $this->exit('Not implemented or missing the Expert Advisor ' . $name);
        }
    }

    /**
     * @param string $message
     * @param int    $exitCode
     */
    private function exit(string $message, int $exitCode = Command::FAILURE): void
    {
        if (Command::FAILURE == $exitCode)
        {
            $this->io->error($message);
        }
        elseif (Command::SUCCESS == $exitCode)
        {
            $this->io->info($message);
        }

        exit($exitCode);
    }

    /**
     * @param string $period
     *
     * @throws Exception
     */
    private function validatePeriod(string $period): void
    {
        $periods = ['M1', 'M5', 'M15', 'M30', 'H1', 'H4', 'D1', 'W1', 'MN1'];

        if (!in_array($period, $periods))
        {
            $this->exit('Invalid period ' . $period . ' supplied, allowed values: ' . implode(', ', $periods));
        }
    }

    /**
     * @param string $deposit
     */
    private function validateDeposit(string $deposit): void
    {
        if (0 > $deposit)
        {
            $this->exit('Invalid deposit amount, must be positive integer, for example: 5000');
        }
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @throws Exception
     */
    private function validateDates(string $from, string $to): void
    {
        $this->validateDate($from);
        $this->validateDate($to);

        $fromDate = DateTime::createFromFormat('Y-m-d', $from);
        $toDate   = DateTime::createFromFormat('Y-m-d', $to);

        if ($fromDate >= $toDate)
        {
            $this->exit('From date ' . $from . ' must be lower than to date ' . $to);
        }

        if ($fromDate >= new DateTime())
        {
            $this->exit('From date ' . $from . ' must be lower than today ' . date('Y-m-d'));
        }

        if ($toDate > new DateTime())
        {
            $this->exit('To date ' . $to . ' must be lower or equal than today ' . date('Y-m-d'));
        }
    }

    /**
     * @param string $date
     */
    private function validateDate(string $date): void
    {
        if (!DateTime::createFromFormat('Y-m-d', $date))
        {
            $this->exit('Invalid date format ' . $date . ', must match YYYY-MM-DD, for example: ' . date('Y-m-d'));
        }
    }

    /**
     * @param string $expertAdvisorName
     *
     * @return AbstractExpertAdvisor
     */
    private function getExpertAdvisorInstance(string $expertAdvisorName): AbstractExpertAdvisor
    {
        $class = AbstractExpertAdvisor::getExpertAdvisorClass($expertAdvisorName);

        // TODO: Load config from .yaml
        $config = new ExpertAdvisorConfig([]);

        return new $class($expertAdvisorName, $config);
    }
}