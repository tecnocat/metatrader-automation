<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\Metatrader\BuildConfigEvent;
use App\Metatrader\Automation\Helper\ConfigHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @Subscriber
 */
class ConfigSubscriber extends AbstractEventSubscriber
{
    private const DATE_FORMAT = 'Y.m.d';

    /**
     * @Dependency
     */
    public ContainerBagInterface $containerBag;

    public function onCreateMetatraderConfigEvent(BuildConfigEvent $event): void
    {
        switch ($event->getType())
        {
            case BuildConfigEvent::EXPERT_ADVISOR_CONFIG_TYPE:
                $event->setConfig($this->getExpertAdvisorConfig($event));

                break;

            case BuildConfigEvent::TERMINAL_CONFIG_TYPE:
                $event->setConfig($this->getTerminalConfig($event));

                break;

            default:
                $event->addError('I don\'t know how to build this config type, unknown type ' . $event->getType());
        }
    }

    private function getExpertAdvisorConfig(BuildConfigEvent $event): array
    {
        $config = [
            'common' => [
                'positions' => '2',
                'deposit'   => $event->getExecutionEvent()->getBacktestEntity()->getDeposit(),
                'currency'  => 'EUR',
                'fitnes'    => '0',
                'genetic'   => '1',
            ],
            'inputs' => [],
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

        return ConfigHelper::fillExpertAdvisorInputs($config, $event->getExecutionEvent()->getExpertAdvisor());
    }

    private function getTerminalConfig(BuildConfigEvent $event): array
    {
        $backtestEntity = $event->getExecutionEvent()->getBacktestEntity();
        $expertAdvisor  = $event->getExecutionEvent()->getExpertAdvisor();
        $parameters     = $this->containerBag->get('metatrader');

        return [
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
                'TestExpertParameters' => ConfigHelper::getExpertAdvisorPath($parameters, $expertAdvisor->getName()),
                'TestSymbol'           => $backtestEntity->getSymbol(),
                'TestPeriod'           => $backtestEntity->getPeriod(),
                'TestModel'            => '0',
                'TestSpread'           => '15',
                'TestOptimization'     => 'false',
                'TestDateEnable'       => 'true',
                'TestFromDate'         => $backtestEntity->getFrom()->format(self::DATE_FORMAT),
                'TestToDate'           => $backtestEntity->getTo()->format(self::DATE_FORMAT),
                'TestReport'           => ConfigHelper::getBacktestReportPath($parameters, $expertAdvisor->getCurrentBacktestSettings()),
                'TestReplaceReport'    => 'true',
                'TestShutdownTerminal' => 'false',
                'TestVisualEnable'     => 'false',
            ],
        ];
    }
}
