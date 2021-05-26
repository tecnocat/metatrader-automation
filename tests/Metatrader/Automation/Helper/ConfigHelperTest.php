<?php

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\ConfigHelper;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    public function getFillExpertAdvisorInputsData(): array
    {
        return [
            [
                [
                    'inputs' => [
                        'foo'   => 'bar',
                        'foo,F' => 'bar',
                        'foo,1' => 'bar',
                        'foo,2' => 'bar',
                        'foo,3' => 'bar',
                    ],
                ],
                [],
                [
                    'something' => 'foo',
                ],
                [
                    'something' => 'bar',
                ],
            ],
            [
                [
                    'config' => [1, 2, 3],
                    'test'   => [4, 5, 6],
                    'inputs' => [
                        'AlfaParameter'   => 'α',
                        'AlfaParameter,F' => 'α',
                        'AlfaParameter,1' => 'α',
                        'AlfaParameter,2' => 'α',
                        'AlfaParameter,3' => 'α',
                        'BetaArgument'    => 'β',
                        'BetaArgument,F'  => 'β',
                        'BetaArgument,1'  => 'β',
                        'BetaArgument,2'  => 'β',
                        'BetaArgument,3'  => 'β',
                        'GammaInput'      => 'γ',
                        'GammaInput,F'    => 'γ',
                        'GammaInput,1'    => 'γ',
                        'GammaInput,2'    => 'γ',
                        'GammaInput,3'    => 'γ',
                    ],
                ],
                [
                    'config' => [1, 2, 3],
                    'test'   => [4, 5, 6],
                ],
                [
                    'alfa'  => 'AlfaParameter',
                    'beta'  => 'BetaArgument',
                    'gamma' => 'GammaInput',
                ],
                [
                    'alfa'    => 'α',
                    'beta'    => 'β',
                    'gamma'   => 'γ',
                    'missing' => 'none',
                ],
            ],
        ];
    }

    public function getGetBacktestReportPath(): array
    {
        return [
            [
                //'C:\Some\Data\Path\1D1NFAFAFSHBJSAFHBSAJFBHASJHF\report-name.html',
                'report-name.html',
                [
                    'data_path' => 'C:\Some\Data\Path',
                ],
                [
                    'backtestReportName' => 'report-name.html',
                ],
            ],
        ];
    }

    public function getGetExpertAdvisorPath(): array
    {
        return [
            [
                //'C:\Some\Data\Path\1D1NFAFAFSHBJSAFHBSAJFBHASJHF\Alfa.ini',
                'Alfa.ini',
                [
                    'data_path' => 'C:\Some\Data\Path',
                ],
                'Alfa',
            ],
            [
                //'C:\Some\Data\Path\1D1NFAFAFSHBJSAFHBSAJFBHASJHF\Beta.ini',
                'Beta.ini',
                [
                    'data_path' => 'C:\Some\Data\Path',
                ],
                'Beta',
            ],
            [
                //'C:\Some\Data\Path\1D1NFAFAFSHBJSAFHBSAJFBHASJHF\Gamma.ini',
                'Gamma.ini',
                [
                    'data_path' => 'C:\Some\Data\Path',
                ],
                'Gamma',
            ],
        ];
    }

    /**
     * @dataProvider getFillExpertAdvisorInputsData
     */
    public function testFillExpertAdvisorInputs(array $expected, array $config, array $alias, array $currentBacktestSettings): void
    {
        $expertAdvisor = $this->createMock(ExpertAdvisorInterface::class);
        $expertAdvisor->expects(self::once())->method('getAlias')->willReturn($alias);
        $expertAdvisor->expects(self::once())->method('getCurrentBacktestSettings')->willReturn($currentBacktestSettings);
        $config = ConfigHelper::fillExpertAdvisorInputs($config, $expertAdvisor);

        self::assertSame($expected, $config);
    }

    /**
     * @dataProvider getGetBacktestReportPath
     */
    public function testGetBacktestReportPath(string $expected, array $parameters, array $currentBacktestSettings): void
    {
        self::assertSame($expected, ConfigHelper::getBacktestReportPath($parameters, $currentBacktestSettings));
    }

    /**
     * @dataProvider getGetExpertAdvisorPath
     */
    public function testGetExpertAdvisorPath(string $expected, array $parameters, string $expertAdvisorName): void
    {
        self::assertSame($expected, ConfigHelper::getExpertAdvisorPath($parameters, $expertAdvisorName));
    }
}
