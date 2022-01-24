<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Metatrader\WriteConfigEvent;
use App\Metatrader\Automation\Helper\ConfigHelper;
use App\Metatrader\Automation\Helper\TerminalHelper;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Subscriber
 */
class ConfigSubscriber extends AbstractEventSubscriber
{
    public function onWriteConfigEvent(WriteConfigEvent $event): void
    {
        switch ($event->getType())
        {
            case WriteConfigEvent::EXPERT_ADVISOR_CONFIG_TYPE:
                $this->writeExpertAdvisorConfig($event);

                break;

            case WriteConfigEvent::TERMINAL_CONFIG_TYPE:
                $this->writeTerminalConfig($event);

                break;

            default:
                $event->addError('I don\'t know how to write this config type, unknown type ' . $event->getType());
        }
    }

    private function writeExpertAdvisorConfig(WriteConfigEvent $event): void
    {
        $backtestExecutionDTO = $event->getExecutionEvent()->getBacktestExecutionDTO();
        $expertAdvisor        = $event->getExecutionEvent()->getExpertAdvisor();
        $terminalDTO          = $event->getExecutionEvent()->getTerminalDTO();
        $config               = [
            'common' => [
                'positions' => '2',
                'deposit'   => $backtestExecutionDTO->initialDeposit,
                'currency'  => 'EUR',
                'fitnes'    => '0',
                'genetic'   => '1',
            ],
            'inputs' => $backtestExecutionDTO->inputs,
            'limits' => [
                'balance_enable'         => '0',
                'balance'                => '200.00',
                'profit_enable'          => '0',
                'profit'                 => '10000.00',
                'marginlevel_enable'     => '0',
                'marginlevel'            => '30.00',
                'maxdrawdown_enable'     => '0',
                'maxdrawdown'            => '70.00',
                'consecloss_enable'      => '0',
                'consecloss'             => '5000.00',
                'conseclossdeals_enable' => '0',
                'conseclossdeals'        => '10.00',
                'consecwin_enable'       => '0',
                'consecwin'              => '10000.00',
                'consecwindeals_enable'  => '0',
                'consecwindeals'         => '30.00',
            ],
        ];

        self::writeXml(ConfigHelper::getExpertAdvisorConfigFile($terminalDTO->path, $expertAdvisor->getName()), $config);
    }

    private function writeTerminalConfig(WriteConfigEvent $event): void
    {
        $backtestExecutionDTO = $event->getExecutionEvent()->getBacktestExecutionDTO();
        $expertAdvisor        = $event->getExecutionEvent()->getExpertAdvisor();
        $terminalDTO          = $event->getExecutionEvent()->getTerminalDTO();
        $config               = [
            '// common' => [
                'Profile'           => 'default',
                'AutoConfiguration' => 'true',
                'DataServer'        => '192.168.0.1:443',
                'EnableDDE'         => 'true',
                'EnableNews'        => 'false',
            ],
            '// expert' => [
                'ExpertsEnable'    => 'true',
                'ExpertsDllImport' => 'true',
                'ExpertsExpImport' => 'true',
                'ExpertsTrades'    => 'true',
            ],
            '// test'   => [
                'TestExpert'           => $expertAdvisor->getName(),
                'TestExpertParameters' => ConfigHelper::getExpertAdvisorConfigFile($terminalDTO->path, $expertAdvisor->getName(), true),
                'TestSymbol'           => $backtestExecutionDTO->symbol,
                'TestPeriod'           => $backtestExecutionDTO->period,
                'TestModel'            => '0',
                'TestSpread'           => '15',
                'TestOptimization'     => 'false',
                'TestDateEnable'       => 'true',
                'TestFromDate'         => $backtestExecutionDTO->from->format(TerminalHelper::TERMINAL_DATE_FORMAT),
                'TestToDate'           => $backtestExecutionDTO->to->format(TerminalHelper::TERMINAL_DATE_FORMAT),
                'TestReport'           => ConfigHelper::getBacktestReportHtmlFile($terminalDTO->path, $backtestExecutionDTO->name, true),
                'TestReplaceReport'    => 'true',
                'TestShutdownTerminal' => 'true',
                'TestVisualEnable'     => 'false',
            ],
        ];

        self::writeIni($terminalDTO->config, $config);
    }

    // TODO: Mix with toXml
    private static function toIni(array $data, bool $deep = false): string
    {
        $ini = $deep ? '' : '// Metatrader Automation - Generated on ' . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $ini .= $key . PHP_EOL . self::toIni($value, true);

                continue;
            }

            $ini .= $key . '=' . $value . PHP_EOL;
        }

        return $ini . PHP_EOL;
    }

    // TODO: Mix with toIni
    private static function toXml(array $data): string
    {
        $xml = '';

        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $xml .= "<$key>" . PHP_EOL . self::toXml($value) . "</$key>" . PHP_EOL . PHP_EOL;

                continue;
            }

            $xml .= $key . '=' . $value . PHP_EOL;
        }

        return $xml;
    }

    // TODO: Fusion with writeXml
    private static function writeIni(string $filename, array $data): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filename, self::toIni($data));
    }

    // TODO: Fusion with writeIni
    private static function writeXml(string $filename, array $data): void
    {
        $filesystem = new Filesystem();
        $filesystem->dumpFile($filename, self::toXml($data));
    }
}
