<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\BuildBacktestReportEntityEvent;
use App\Metatrader\Automation\Event\FindEntityEvent;
use App\Metatrader\Automation\Helper\BacktestReportHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Finder\Finder;

class MetatraderBacktestImportCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument('directory', InputArgument::REQUIRED, 'The absolute path of the backtest reports')
            ->setDescription('Import a Metatrader backtest reports based on selected path')
            ->setHelp('This command allow you to import Metatrader backtest reports to the database')
            ->setName($this->generateName())
        ;
    }

    protected function process(InputInterface $input): int
    {
        try
        {
            $finder = new Finder();
            $finder->files()->in($directory = $input->getArgument('directory'))->name('*.html');
        }
        catch (\Exception $exception)
        {
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }

        if (!$finder->hasResults())
        {
            $this->error("The directory $directory does not contains any backtest report");

            return Command::FAILURE;
        }

        foreach ($finder as $file)
        {
            $name = $file->getFilename();
            $this->comment("Parsing backtest report $name ...");

            $criteria = ['name' => $name];
            $event    = $this->dispatch(new FindEntityEvent(BacktestReportEntity::class, $criteria));

            if ($event->existsEntity())
            {
                $this->comment("Backtest report $name already imported, skipping ...");

                continue;
            }

            $parameters = BacktestReportHelper::parseFile($file->getRealPath());
            $event      = $this->dispatch(new BuildBacktestReportEntityEvent($parameters));

            if ($event->hasErrors())
            {
                foreach ($event->getErrors() as $error)
                {
                    $this->error($error);
                }

                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}