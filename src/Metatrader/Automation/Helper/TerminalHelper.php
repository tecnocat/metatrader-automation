<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\DTO\TerminalDTO;
use App\Metatrader\Automation\Event\Metatrader\FindTerminalEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class TerminalHelper
{
    public const TERMINAL_CLUSTER_EXE_PATTERN  = '/[A-Z]:\\\\MT[4-5]-\d+\\\\terminal(64)?\.exe/';
    public const TERMINAL_CLUSTER_ID           = '/[A-Z]:\\\\MT[4-5]-(\d+)\\\\/';
    public const TERMINAL_CLUSTER_PATH_PATTERN = '/[A-Z]:\\\\MT[4-5]-\d+\\\\/';
    public const TERMINAL_DATE_FORMAT          = 'Y.m.d';

    private static array $cache = [];

    public static function findOneFree(string $dataPath): TerminalDTO
    {
        self::createCluster($dataPath);
        $terminals = self::findTerminals($dataPath);
        $timeout   = 0;

        while (true)
        {
            self::updateTerminalStatus($terminals);

            foreach ($terminals as $terminalDTO)
            {
                if (!self::isSupported($terminalDTO) || !self::isCluster($terminalDTO))
                {
                    continue;
                }

                if ($terminalDTO->isBusy())
                {
                    sleep(1);

                    continue;
                }

                return $terminalDTO;
            }

            if ($timeout++ > count($terminals) * 10)
            {
                throw new \RuntimeException('Timeout waiting a free terminal configured in path ' . $dataPath);
            }
        }
    }

    public static function syncExpertAdvisor(FindTerminalEvent $event): void
    {
        $originalTerminalDTO = self::findOriginalTerminal($event->getDataPath());
        $expertAdvisor       = $event->getExecutionEvent()->getExpertAdvisor();
        $terminalDTO         = $event->getTerminalDTO();
        $version             = $originalTerminalDTO->version;
        $path                = implode(DIRECTORY_SEPARATOR, ['MQL' . $version, 'Experts', $expertAdvisor->getName() . '.ex' . $version]);
        $source              = $originalTerminalDTO->path . DIRECTORY_SEPARATOR . $path;
        $target              = $terminalDTO->path . DIRECTORY_SEPARATOR . $path;

        if (CacheHelper::getCache(__METHOD__, $target))
        {
            return;
        }

        $filesystem = new Filesystem();
        $filesystem->copy($source, $target, true);
        CacheHelper::setCache(__METHOD__, $target, true);
    }

    private static function createCluster(string $dataPath): void
    {
        if (WindowsHelper::getNumberOfCores() === self::getNumberOfClusterTerminals($dataPath))
        {
            return;
        }

        $originalTerminalDTO = self::findOriginalTerminal($dataPath);
        $sourcePath          = dirname($originalTerminalDTO->exe);
        $filesystem          = new Filesystem();
        $paths               = [
            DIRECTORY_SEPARATOR . 'config',
            DIRECTORY_SEPARATOR . 'history',
            DIRECTORY_SEPARATOR . 'MQL4' . DIRECTORY_SEPARATOR . 'Experts',
            DIRECTORY_SEPARATOR . 'MQL4' . DIRECTORY_SEPARATOR . 'Include',
            DIRECTORY_SEPARATOR . 'templates',
        ];

        for ($i = 1; $i <= WindowsHelper::getNumberOfCores(); ++$i)
        {
            // TODO: Only supports MT4 for now
            $clusterPath = implode(DIRECTORY_SEPARATOR, [mb_substr($sourcePath, 0, 2), 'MT4-' . $i]);
            $terminalExe = $clusterPath . DIRECTORY_SEPARATOR . 'terminal.exe';

            if (!is_dir($clusterPath))
            {
                $filesystem->mirror($sourcePath, $clusterPath);
                exec(sprintf('"%s" /?', $terminalExe));
            }

            foreach (self::findTerminals($dataPath, false) as $terminalDTO)
            {
                if ($terminalExe === $terminalDTO->exe)
                {
                    foreach ($paths as $path)
                    {
                        $filesystem->mirror($originalTerminalDTO->path . $path, $terminalDTO->path . $path, null, ['override' => true]);
                    }

                    if (!file_exists($terminalDTO->path . DIRECTORY_SEPARATOR . '_cluster-' . $i))
                    {
                        mkdir($terminalDTO->path . DIRECTORY_SEPARATOR . '_cluster-' . $i);
                    }

                    break;
                }
            }
        }
    }

    private static function findOriginalTerminal(string $dataPath): TerminalDTO
    {
        $terminals = self::findTerminals($dataPath);

        foreach ($terminals as $terminalDTO)
        {
            // TODO: Ignore MT4 cluster with a some created cluster file like .cluster in the terminalExe directory
            // TODO: Find the original based on fixed parameter in config, this only take the first MT4 non clustered available
            if (self::isSupported($terminalDTO) && !self::isCluster($terminalDTO))
            {
                return $terminalDTO;
            }
        }

        throw new \RuntimeException('Unable to find the original terminal in configured path ' . $dataPath);
    }

    /**
     * @return TerminalDTO[]
     */
    private static function findTerminals(string $dataPath, bool $cached = true): array
    {
        $cacheKey = md5(__FUNCTION__ . $dataPath);

        if (isset(self::$cache[$cacheKey]) && $cached)
        {
            return self::$cache[$cacheKey];
        }

        $finder = new Finder();
        $finder
            ->files()
            ->in($dataPath)
            ->depth(1)
            ->name('origin.txt')
        ;
        $terminals = [];

        foreach ($finder as $file)
        {
            $id          = $file->getRelativePath();
            $path        = dirname($file->getPathname());
            $config      = ConfigHelper::getTerminalConfigFile($path);
            $exe         = self::getTerminalExe(preg_replace('/[[:^print:]]/', '', file_get_contents($file->getPathname())));
            $version     = self::getTerminalVersion($path);
            $terminals[] = new TerminalDTO(
                [
                    'id'      => $id,
                    'config'  => $config,
                    'exe'     => $exe,
                    'path'    => $path,
                    'version' => $version,
                ]
            );
        }

        if (empty($terminals))
        {
            throw new \RuntimeException('Not found any terminal configured in path ' . $dataPath);
        }

        usort($terminals, function ($a, $b)
        {
            preg_match(self::TERMINAL_CLUSTER_ID, $a->exe, $matchesA);
            preg_match(self::TERMINAL_CLUSTER_ID, $b->exe, $matchesB);

            if (isset($matchesA[1], $matchesB[1]))
            {
                return $matchesA[1] > $matchesB[1] ? 1 : -1;
            }

            return isset($matchesA[1]) ? -1 : 1;
        });

        return self::$cache[$cacheKey] = $terminals;
    }

    private static function getNumberOfClusterTerminals(string $dataPath): int
    {
        $count     = 0;
        $terminals = self::findTerminals($dataPath);

        foreach ($terminals as $terminalDTO)
        {
            if (self::isSupported($terminalDTO) && self::isCluster($terminalDTO))
            {
                ++$count;
            }
        }

        return $count;
    }

    private static function getTerminalExe(string $terminalPath): string
    {
        foreach (['terminal.exe', 'terminal64.exe'] as $binary)
        {
            if (is_file($terminalExe = $terminalPath . DIRECTORY_SEPARATOR . $binary))
            {
                return $terminalExe;
            }
        }

        throw new \RuntimeException("Unable to find terminal executable for $terminalPath, may be empty data folder?");
    }

    private static function getTerminalVersion(string $terminalPath): int
    {
        foreach (['MQL4', 'MQL5'] as $mqlVersion)
        {
            if (is_dir($terminalPath . DIRECTORY_SEPARATOR . $mqlVersion))
            {
                return (int) mb_substr($mqlVersion, -1);
            }
        }

        return -1;
    }

    private static function isCluster(TerminalDTO $terminalDTO): bool
    {
        return (bool) preg_match(self::TERMINAL_CLUSTER_PATH_PATTERN, $terminalDTO->exe);
    }

    private static function isSupported(TerminalDTO $terminalDTO): bool
    {
        return 4 === $terminalDTO->version;
    }

    /**
     * @param TerminalDTO[] $terminals
     */
    private static function updateTerminalStatus(array $terminals): void
    {
        $terminalsRunning = WindowsHelper::getTerminalsRunning();

        foreach ($terminals as $terminalDTO)
        {
            $terminalDTO->setFree();

            if (in_array($terminalDTO->exe, $terminalsRunning, true))
            {
                $terminalDTO->setBusy();
            }
        }
    }
}
