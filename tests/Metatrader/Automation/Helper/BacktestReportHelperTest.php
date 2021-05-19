<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\BacktestReportHelper;
use PHPUnit\Framework\TestCase;

class BacktestReportHelperTest extends TestCase
{
    public function testParseFile()
    {
        $expected = [
            'name'                     => 'BacktestReportHelperTestFile.html',
            'expertAdvisor'            => 'Prudencio',
            'symbol'                   => '.SPAINCash',
            'period'                   => 'H4',
            'from'                     => '2016-06-01',
            'to'                       => '2016-07-01',
            'model'                    => 'Every tick',
            'barsInTest'               => '188',
            'ticksModelled'            => '1066974',
            'modellingQuality'         => '99.90',
            'mismatchedChartsErrors'   => '0',
            'initialDeposit'           => '5000',
            'spread'                   => -1,
            'totalNetProfit'           => '6131.36',
            'grossProfit'              => '38560.77',
            'grossLoss'                => '-32429.40',
            'profitFactor'             => '1.19',
            'expectedPayoff'           => '5.99',
            'absoluteDrawdown'         => '1584.94',
            'maximalDrawdown'          => '1830.92',
            'relativeDrawdown'         => '34.90',
            'totalTrades'              => '1024',
            'shortPositions'           => '632',
            'longPositions'            => '392',
            'profitTrades'             => '698',
            'lossTrades'               => '326',
            'largestProfitTrade'       => '1377.97',
            'largestLossTrade'         => '-1257.07',
            'averageProfitTrade'       => '55.24',
            'averageLossTrade'         => '-99.48',
            'maximumConsecutiveWins'   => '21',
            'maximumConsecutiveLosses' => '11',
            'maximalConsecutiveProfit' => '9632.61',
            'maximalConsecutiveLoss'   => '-6172.77',
            'averageConsecutiveWins'   => '5',
            'averageConsecutiveLosses' => '2',
            'parameters'               => 'a:7:{s:8:"LogLevel";s:1:"1";s:9:"Distancia";s:3:"950";s:13:"Multiplicador";s:1:"1";s:9:"Cobertura";s:4:"2000";s:7:"Perdida";s:4:"4000";s:9:"Beneficio";s:2:"10";s:11:"Exponencial";s:5:"false";}',
        ];
        static::assertSame($expected, BacktestReportHelper::parseFile(__DIR__ . DIRECTORY_SEPARATOR . 'BacktestReportHelperTestFile.html'));
    }
}
