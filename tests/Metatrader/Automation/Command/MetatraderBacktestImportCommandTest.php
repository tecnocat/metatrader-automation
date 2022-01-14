<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MetatraderBacktestImportCommandTest extends KernelTestCase
{
    public function getExecuteData(): array
    {
        return [
            [
                '[ERROR] The directory ' . __DIR__ . ' does not contains any backtest report',
                [
                    'directory' => __DIR__,
                ],
            ],
            [
                '[ERROR] The "' . __DIR__ . 'a1b2c3d4e5f6" directory does not exist.',
                [
                    'directory' => __DIR__ . 'a1b2c3d4e5f6',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getExecuteData
     */
    public function testExecute(string $expected, array $arguments): void
    {
        $kernel        = static::createKernel();
        $application   = new Application($kernel);
        $command       = $application->find('metatrader:backtest:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute($arguments);

        static::assertStringContainsString($expected, preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $commandTester->getDisplay()));
    }
}
