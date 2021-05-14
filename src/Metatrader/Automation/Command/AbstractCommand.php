<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Helper\ClassHelper;
use App\Metatrader\Automation\Interfaces\DispatcherInterface;
use App\Metatrader\Automation\Interfaces\EventInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractCommand extends Command implements DispatcherInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private SymfonyStyle             $io;

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
        $this->io->comment($message);
    }

    final protected function error(string $message): void
    {
        $this->io->error($message);
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        return $this->process($input, $output);
    }

    final protected function generateName(): string
    {
        return mb_substr(ClassHelper::getClassNameColons($this), 0, -8);
    }

    final protected function hasErrors(EventInterface $event): bool
    {
        if ($event->hasErrors())
        {
            foreach ($event->getErrors() as $error)
            {
                $this->error($error);
            }

            return false;
        }

        return true;
    }

    final protected function table(array $headers, array $rows): void
    {
        $this->io->table($headers, $rows);
    }
}
