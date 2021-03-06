<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Helper\BacktestReportHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class MetatraderBacktestImportCommand extends AbstractCommand
{
    public function process(): int
    {
        try
        {
            $finder = new Finder();
            $finder->files()->in($directory = $this->getArgument('directory'))->name('*.html');
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

        $progressBar = $this->getProgressBar($finder->count());

        // TODO: Make a global event to import to allow call from generate command
        foreach ($finder as $file)
        {
            // TODO: Delete helper, make an event instead
            if (!BacktestReportHelper::isValid($file->getRealPath()))
            {
                $progressBar->setMessage('<fg=cyan>' . $file->getFilename() . '</> <fg=yellow>invalid results, skip...</>');
                $progressBar->advance();

                continue;
            }

            // TODO: Delete helper, make an event instead
            $parameters      = BacktestReportHelper::readFile($file->getRealPath());
            $criteria        = ['name' => $parameters['name']];
            $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                $progressBar->setMessage('<fg=cyan>' . $file->getFilename() . '</> <fg=yellow>already imported, skip...</>');
                $progressBar->advance();

                continue;
            }

            $buildEntityEvent = new BuildEntityEvent(BacktestReportEntity::class, $parameters);
            $this->dispatch($buildEntityEvent);

            if ($this->hasErrors($buildEntityEvent))
            {
                return Command::FAILURE;
            }

            $saveEntityEvent = new SaveEntityEvent($buildEntityEvent->getEntity());
            $this->dispatch($saveEntityEvent);

            if ($this->hasErrors($saveEntityEvent))
            {
                return Command::FAILURE;
            }

            $progressBar->setMessage('<fg=cyan>' . $file->getFilename() . '</> <fg=green>imported successfully!</>');
            $progressBar->advance();
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }

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
}
