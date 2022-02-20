<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Helper\BacktestReportHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class MetatraderBacktestBackupCommand extends AbstractCommand
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

        $backupDirectory = 'MetatraderBacktestReportBackups';
        $filesystem      = new Filesystem();
        $progressBar     = $this->getProgressBar($finder->count());

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
            $sourceHtml         = $file->getRealPath();
            $sourceGif          = str_replace('.html', '.gif', $sourceHtml);
            $parameters         = BacktestReportHelper::readFile($sourceHtml);
            $backtestReportName = str_replace(':', '-', $parameters['name']) . '.html';
            $expertAdvisorName  = $parameters['expertAdvisorName'];
            $symbol             = $parameters['symbol'];
            $period             = $parameters['period'];
            $date               = $parameters['from'] . '-' . $parameters['to'];

            for ($ascii = ord('A'); $ascii <= ord('Z'); ++$ascii)
            {
                $drive = chr($ascii) . ':\\';

                if (!is_dir($drive))
                {
                    continue;
                }

                $path = $drive . implode(DIRECTORY_SEPARATOR, [$backupDirectory, $expertAdvisorName, $symbol, $period, $date]);

                if (!is_dir($path))
                {
                    if (!mkdir($path, 0777, true))
                    {
                        $this->error('Unable to create Metatrader backup directory ' . $path);

                        return Command::FAILURE;
                    }
                }

                $targetHtml = $path . DIRECTORY_SEPARATOR . $backtestReportName;
                $targetGif  = str_replace('.html', '.gif', $targetHtml);
                $filesystem->copy($sourceHtml, $targetHtml);
                $filesystem->copy($sourceGif, $targetGif);
                $reading = fopen($targetHtml, 'r');
                $writing = fopen($targetHtml . '.tmp', 'w');

                while (!feof($reading))
                {
                    fputs($writing, str_replace(basename($sourceGif), basename($targetGif), fgets($reading)));
                }

                fclose($reading);
                fclose($writing);
                rename($targetHtml . '.tmp', $targetHtml);
            }

            unlink($sourceHtml);
            unlink($sourceGif);
            $progressBar->setMessage('<fg=cyan>' . $file->getFilename() . '</> <fg=green>backup successfully!</>');
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
            ->setDescription('Backup a Metatrader backtest reports on every detected system drive')
            ->setHelp('This command allow you to backup Metatrader backtest reports to all system drives')
            ->setName($this->generateName())
        ;
    }
}
