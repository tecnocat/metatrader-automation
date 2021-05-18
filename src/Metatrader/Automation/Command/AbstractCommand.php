<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Helper\ClassHelper;
use App\Metatrader\Automation\Interfaces\CommandInterface;
use App\Metatrader\Automation\Interfaces\DispatcherInterface;
use App\Metatrader\Automation\Interfaces\EventInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractCommand extends Command implements CommandInterface, DispatcherInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private InputInterface           $input;
    private SymfonyStyle             $io;
    private OutputInterface          $output;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct();
    }

    final public function dispatch(EventInterface $event): EventInterface
    {
        $this->eventDispatcher->dispatch($event, $event->getEventName());

        return $event;
    }

    /**
     * @param array|string $message
     */
    final protected function comment($message): void
    {
        $this->getIO()->comment($message);
    }

    final protected function error(string $message): void
    {
        $this->getIO()->error($message);
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input  = $input;
        $this->output = $output;

        return $this->process();
    }

    final protected function generateName(): string
    {
        return mb_substr(ClassHelper::getClassNameColon($this), 0, -8);
    }

    /**
     * @return null|string|string[]
     */
    final protected function getArgument(string $name)
    {
        return $this->input->getArgument($name);
    }

    final protected function getArguments(): array
    {
        return $this->input->getArguments();
    }

    /**
     * @return null|bool|string|string[]
     */
    final protected function getOption(string $name)
    {
        return $this->input->getOption($name);
    }

    final protected function getOptions(): array
    {
        return $this->input->getOptions();
    }

    final protected function getProgressBar(int $total): ProgressBar
    {
        $spacer = str_repeat(' ', mb_strlen((string) $total) * 2 + 2);
        $format = "\n$spacer %message%\n\n <fg=yellow>%current%</>/<fg=green>%max%</> [%bar%] %percent:3s%%\n";
        ProgressBar::setFormatDefinition('custom', $format);
        $progressBar = new ProgressBar($this->output, $total);
        $progressBar->setBarWidth(80);
        $progressBar->setFormat('custom');
        $progressBar->setBarCharacter('<fg=green>|</>');
        $progressBar->setProgressCharacter('<fg=yellow>></>');
        $progressBar->setEmptyBarCharacter('<fg=red>-</>');

        return $progressBar;
    }

    final protected function hasErrors(EventInterface $event): bool
    {
        if ($event->hasErrors())
        {
            foreach ($event->getErrors() as $error)
            {
                $this->error($error);
            }

            return true;
        }

        return false;
    }

    final protected function table(array $headers, array $rows): void
    {
        $this->getIO()->table($headers, $rows);
    }

    private function getIO(): StyleInterface
    {
        if (!isset($this->io))
        {
            $this->io = new SymfonyStyle($this->input, $this->output);
        }

        return $this->io;
    }
}
