<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;

class ConfigHelper
{
    public static function fillExpertAdvisorInputs(array $config, ExpertAdvisorInterface $expertAdvisor): array
    {
        $alias = $expertAdvisor->getAlias();

        foreach ($expertAdvisor->getCurrentBacktestSettings() as $backtestSettingName => $backtestSettingValue)
        {
            if (!isset($alias[$backtestSettingName]))
            {
                continue;
            }

            $config['inputs'][$alias[$backtestSettingName]]        = $backtestSettingValue;
            $config['inputs'][$alias[$backtestSettingName] . ',F'] = $backtestSettingValue;
            $config['inputs'][$alias[$backtestSettingName] . ',1'] = $backtestSettingValue;
            $config['inputs'][$alias[$backtestSettingName] . ',2'] = $backtestSettingValue;
            $config['inputs'][$alias[$backtestSettingName] . ',3'] = $backtestSettingValue;
        }

        return $config;
    }

    public static function getBacktestReportPath(array $parameters, array $currentBacktestSettings): string
    {
        $terminalDataPath   = self::getMainTerminalPath($parameters['data_path']);
        $backtestReportPath = $terminalDataPath . DIRECTORY_SEPARATOR . $currentBacktestSettings['backtestReportName'];

        return self::getRelativePath($backtestReportPath, $terminalDataPath);
    }

    public static function getExpertAdvisorPath(array $parameters, string $expertAdvisorName): string
    {
        $terminalDataPath  = self::getMainTerminalPath($parameters['data_path']);
        $expertAdvisorPath = $terminalDataPath . DIRECTORY_SEPARATOR . $expertAdvisorName . '.ini';

        return self::getRelativePath($expertAdvisorPath, $terminalDataPath);
    }

    private static function getMainTerminalPath(string $dataPath): string
    {
        // TODO: Handle dinamically, this is just a test
        return $dataPath . DIRECTORY_SEPARATOR . '1D1NFAFAFSHBJSAFHBSAJFBHASJHF';
    }

    private static function getRelativePath(string $fullPath, string $relativePath): string
    {
        return ltrim(str_replace(rtrim($relativePath, DIRECTORY_SEPARATOR), '', $fullPath), DIRECTORY_SEPARATOR);
    }
}
