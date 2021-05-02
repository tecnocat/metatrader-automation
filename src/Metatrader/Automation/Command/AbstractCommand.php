<?php

namespace App\Metatrader\Automation\Command;

use App\Metatrader\Automation\Event\AbstractEvent;
use App\Metatrader\Automation\Helper\ClassTools;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractCommand
 *
 * @package App\Metatrader\Automation\Command
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    /**
     * AbstractCommand constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        return $this->command($input, $output);
    }

    /**
     * @return string
     */
    final protected function generateName(): string
    {
        return substr(ClassTools::getClassNameDashed($this), 0, -8);
    }

    /**
     * @param AbstractEvent $event
     *
     * @return object
     */
    final protected function dispatch(AbstractEvent $event): object
    {
        return $this->eventDispatcher->dispatch($event, $event->getEventName());
    }

    /**
     * @param string|array $message
     */
    final protected function comment($message): void
    {
        $this->io->comment($message);
    }

    /**
     * @param array $headers
     * @param array $rows
     */
    final protected function table(array $headers, array $rows): void
    {
        $this->io->table($headers, $rows);
    }

    /**
     * @param string $message
     */
    final protected function error(string $message)
    {
        $this->io->error($message);
    }

    /**
     * @param string $message
     * @param int    $exitCode
     */
    final protected function exit(string $message, int $exitCode = Command::FAILURE): void
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
}