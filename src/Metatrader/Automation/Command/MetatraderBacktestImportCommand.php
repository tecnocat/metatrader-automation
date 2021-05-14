<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Entity\BacktestReportEntity;
use App\Metatrader\Automation\Event\Entity\BuildEntityEvent;
use App\Metatrader\Automation\Event\Entity\FindEntityEvent;
use App\Metatrader\Automation\Event\Entity\SaveEntityEvent;
use App\Metatrader\Automation\Helper\BacktestReportHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

    protected function process(InputInterface $input, OutputInterface $output): int
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

        $progressBar = $this->getProgressBar($output, $finder->count());

        foreach ($finder as $file)
        {
            $name            = $file->getFilename();
            $criteria        = ['name' => $name];
            $findEntityEvent = new FindEntityEvent(BacktestReportEntity::class, $criteria);
            $this->dispatch($findEntityEvent);

            if ($findEntityEvent->isFound())
            {
                $progressBar->setMessage("<fg=cyan>$name</> <fg=yellow>already imported, skip...</>");
                $progressBar->advance();

                continue;
            }

            $buildEntityEvent = new BuildEntityEvent(BacktestReportEntity::class, BacktestReportHelper::parseFile($file->getRealPath()));
            $this->dispatch($buildEntityEvent);

            if (!$this->hasErrors($buildEntityEvent))
            {
                return Command::FAILURE;
            }

            $saveEntityEvent = new SaveEntityEvent($buildEntityEvent->getEntity());
            $this->dispatch($saveEntityEvent);

            if (!$this->hasErrors($saveEntityEvent))
            {
                return Command::FAILURE;
            }

            $progressBar->setMessage("<fg=cyan>$name</> <fg=green>imported successfully!</>");
            $progressBar->advance();
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }

    private function getProgressBar(OutputInterface $output, int $total): ProgressBar
    {
        $spacer = str_repeat(' ', mb_strlen((string) $total) * 2 + 2);
        $format = "\n$spacer %message%\n\n <fg=yellow>%current%</>/<fg=green>%max%</> [%bar%] %percent:3s%%\n";
        ProgressBar::setFormatDefinition('custom', $format);
        $progressBar = new ProgressBar($output, $total);
        $progressBar->setBarWidth(80);
        $progressBar->setFormat('custom');
        $progressBar->setBarCharacter('<fg=green>|</>');
        $progressBar->setProgressCharacter('<fg=yellow>></>');
        $progressBar->setEmptyBarCharacter('<fg=red>-</>');

        return $progressBar;
    }
}
