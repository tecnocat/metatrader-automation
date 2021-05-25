<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\DTO\TerminalDTO;
use Symfony\Component\Finder\Finder;

class TerminalHelper
{
    private static array $cache;

    /**
     * @return TerminalDTO[]
     */
    public static function findAll(string $dataPath): array
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

        if (empty($terminals))
        {
            throw new \RuntimeException('Not found any terminal configured in path ' . $dataPath);
        }

        return self::$cache[__FUNCTION__][$dataPath] = $terminals;
    }

    public static function findOneFree(string $dataPath): TerminalDTO
    {
        while (true)
        {
            foreach (self::findAll($dataPath) as $terminalDTO)
            {
                if (!$terminalDTO->isSupported() || !$terminalDTO->isCluster())
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
}
