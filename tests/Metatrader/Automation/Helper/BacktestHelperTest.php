<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\BacktestHelper;
use PHPUnit\Framework\TestCase;

class BacktestHelperTest extends TestCase
{
    public function getAddBacktestNameData(): array
    {
        return [
            [
                [
                    'expertAdvisor' => 'Bartolo',
                    'symbol'        => 'EURUSD',
                    'period'        => 'M15',
                    'deposit'       => '500',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                    'name'          => 'Bartolo:EURUSD:M15:500:2013-01-01:2014-01-01',
                ],
                [
                    'expertAdvisor' => 'Bartolo',
                    'symbol'        => 'EURUSD',
                    'period'        => 'M15',
                    'deposit'       => '500',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                ],
            ],
            [
                [
                    'expertAdvisor' => 'Fermin',
                    'symbol'        => 'GBPUSD',
                    'period'        => 'H1',
                    'deposit'       => '1000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                    'name'          => 'Fermin:GBPUSD:H1:1000:2013-01-01:2014-01-01',
                ],
                [
                    'expertAdvisor' => 'Fermin',
                    'symbol'        => 'GBPUSD',
                    'period'        => 'H1',
                    'deposit'       => '1000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                ],
            ],
            [
                [
                    'expertAdvisor' => 'Prudencio',
                    'symbol'        => 'USDJPY',
                    'period'        => 'H4',
                    'deposit'       => '3000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                    'name'          => 'Prudencio:USDJPY:H4:3000:2013-01-01:2014-01-01',
                ],
                [
                    'expertAdvisor' => 'Prudencio',
                    'symbol'        => 'USDJPY',
                    'period'        => 'H4',
                    'deposit'       => '3000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                ],
            ],
            [
                [
                    'expertAdvisor' => 'Wilson',
                    'symbol'        => 'USDCHF',
                    'period'        => 'D1',
                    'deposit'       => '5000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                    'name'          => 'Wilson:USDCHF:D1:5000:2013-01-01:2014-01-01',
                ],
                [
                    'expertAdvisor' => 'Wilson',
                    'symbol'        => 'USDCHF',
                    'period'        => 'D1',
                    'deposit'       => '5000',
                    'from'          => '2013-01-01',
                    'to'            => '2014-01-01',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getAddBacktestNameData
     */
    public function testAddBacktestName(array $expected, array $data): void
    {
        self::assertSame($expected, BacktestHelper::addBacktestName($data));
    }
}
