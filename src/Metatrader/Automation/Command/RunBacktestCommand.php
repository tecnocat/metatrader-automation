<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Event\FactoryBuildBacktestEvent;
use App\Metatrader\Automation\Event\MetatraderBacktestExecutionEvent;
use App\Metatrader\Automation\Event\PrepareBacktestParametersCommandEvent;
use App\Metatrader\Automation\Event\ValidateBacktestParametersModelEvent;
use App\Metatrader\Automation\Model\BacktestModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

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

    protected function process(InputInterface $input): int
    {
        $event = new PrepareBacktestParametersCommandEvent($input);
        $this->dispatch($event);

        $event = new ValidateBacktestParametersModelEvent(BacktestModel::class, $event->getParameters());
        $this->dispatch($event);

        if (!$event->isValid())
        {
            foreach ($event->getErrors() as $error)
            {
                $this->error($error->getMessage());
            }

            $this->exit('Validation failed.');
        }

        $model   = $event->getModel();
        $headers = ['Name', 'Symbol', 'Period', 'Deposit', 'From', 'To'];
        $rows    = [
            [
                $model->getName(),
                $model->getSymbol(),
                $model->getPeriod(),
                $model->getDeposit(),
                $model->getFrom()->format('Y-m-d'),
                $model->getTo()->format('Y-m-d'),
            ],
        ];
        $this->comment('Executing Metatrader Automation...');
        $this->table($headers, $rows);

        $event = new FactoryBuildBacktestEvent($model);
        $this->dispatch($event);

        $event = new MetatraderBacktestExecutionEvent($event->getBacktest());
        $this->dispatch($event);

        if ($event->hasErrors())
        {
            foreach ($event->getErrors() as $error)
            {
                $this->error($error);
            }

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
