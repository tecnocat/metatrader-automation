<?php

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Backtest\Backtest;
use App\Metatrader\Automation\Event\PrepareBacktestParametersCommandEvent;
use App\Metatrader\Automation\Event\ValidateBacktestParametersModelEvent;
use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use App\Metatrader\Automation\ExpertAdvisor\ExpertAdvisorConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class RunBacktestCommand
 *
 * @package App\Metatrader\Automation\Command
 */
class RunBacktestCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the Expert Advisor')
            ->addArgument('symbol', InputArgument::REQUIRED, 'The symbol to test with the Expert Advisor')
            ->addArgument('period', InputArgument::REQUIRED, 'The period to test with the Expert Advisor')
            ->addArgument('deposit', InputArgument::REQUIRED, 'The amount of equity to test with the Expert Advisor')
            ->addArgument('from', InputArgument::REQUIRED, 'The from date to test with the Expert Advisor')
            ->addArgument('to', InputArgument::REQUIRED, 'The to date to test with the Expert Advisor')
            ->setDescription('Run a Metatrader backtest')
            ->setHelp('This command allow you to run multiple Metatrader instances and backtests automatically')
            ->setName($this->generateName())
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function command(InputInterface $input, OutputInterface $output): int
    {
        $event = new PrepareBacktestParametersCommandEvent($input);
        $this->dispatch($event);

        $event = new ValidateBacktestParametersModelEvent($event->getParameters());
        $this->dispatch($event);

        if (!$event->isValid())
        {
            /** @var ConstraintViolationInterface $error */
            foreach ($event->getErrors() as $error)
            {
                $this->error($error->getMessage());
            }

            $this->exit('Validation failed.');
        }

        $backtestModel = $event->getModel();
        $name          = $backtestModel->getName();
        $symbol        = $backtestModel->getSymbol();
        $period        = $backtestModel->getPeriod();
        $deposit       = $backtestModel->getDeposit();
        $from          = $backtestModel->getFrom();
        $to            = $backtestModel->getTo();
        $headers       = ['Name', 'Symbol', 'Period', 'Deposit', 'From', 'To'];
        $rows          = [[$name, $symbol, $period, $deposit, $from->format('Y-m-d'), $to->format('Y-m-d')]];
        $this->comment('Executing Metatrader Automation...');
        $this->table($headers, $rows);

        // TODO: Use construct event
        $expertAdvisor = $this->getExpertAdvisorInstance($name);
        $backtest      = new Backtest();
        $backtest->setExpertAdvisor($expertAdvisor);
        $backtest->setSymbol($symbol);
        $backtest->setPeriod($period);
        $backtest->setDeposit($deposit);
        $backtest->setFromDate($from);
        $backtest->setToDate($to);

        // TODO: Event loop between dates

        return Command::SUCCESS;
    }

    /**
     * TODO: Move to an event
     *
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