<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\DTO\TerminalDTO;
use Symfony\Component\Finder\Finder;

class TerminalHelper
{
    public const TERMINAL_CLUSTER_EXE_PATTERN  = '/[A-Z]{1}:\\\\MT[4-5]-\d+\\\\terminal(64)?\.exe/';
    public const TERMINAL_CLUSTER_PATH_PATTERN = '/[A-Z]{1}:\\\\MT[4-5]-\d+\\\\/';

    private static array $cache;

    /**
     * @return TerminalDTO[]
     */
    public static function findAllTerminalDTOs(string $dataPath): array
    {
        $cache = self::$cache[__FUNCTION__][$dataPath] ?? [];

        if (!empty($cache))
        {
            return $cache;
        }

        $finder = new Finder();
        $finder->files()->in($dataPath)->depth(1)->name('origin.txt');
        $terminals = [];

        foreach ($finder as $file)
        {
            $terminalExe     = self::getTerminalExe(preg_replace('/[[:^print:]]/', '', file_get_contents($file->getPathname())));
            $terminalId      = $file->getRelativePath();
            $terminalPath    = dirname($file->getPathname());
            $terminalConfig  = ConfigHelper::getTerminalConfigFile($terminalPath);
            $terminalVersion = self::getTerminalVersion($terminalPath);
            $terminals[]     = new TerminalDTO(
                [
                    'terminalConfig'  => $terminalConfig,
                    'terminalExe'     => $terminalExe,
                    'terminalId'      => $terminalId,
                    'terminalPath'    => $terminalPath,
                    'terminalVersion' => $terminalVersion,
                ]
            );
        }

        /*
         * TODO: What happens if no terminals exists in cluster? auto-build? manually copy?
         */
        if (empty($terminals))
        {
            throw new \RuntimeException('Not found any terminal configured in path ' . $dataPath);
        }

        return self::$cache[__FUNCTION__][$dataPath] = $terminals;
    }

    public static function findOneFree(string $dataPath): TerminalDTO
    {
        $terminalDTOs = self::findAllTerminalDTOs($dataPath);

        while (true)
        {
            self::updateTerminalStatus($terminalDTOs);

            foreach ($terminalDTOs as $terminalDTO)
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

            sleep(count($terminalDTOs));
        }
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
        return (bool) preg_match(self::TERMINAL_CLUSTER_PATH_PATTERN, $terminalDTO->terminalExe);
    }

    private static function isSupported(TerminalDTO $terminalDTO): bool
    {
        return 4 === $terminalDTO->terminalVersion;
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

            if (in_array($terminalDTO->terminalExe, $terminalsRunning, true))
            {
                $terminalDTO->setBusy();
            }
        }
    }
}
