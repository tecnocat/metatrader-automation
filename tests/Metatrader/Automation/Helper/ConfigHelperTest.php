<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\ConfigHelper;
use App\Metatrader\Automation\Interfaces\ExpertAdvisorInterface;
use PHPUnit\Framework\TestCase;

class ConfigHelperTest extends TestCase
{
    public function getGetBacktestReportHtmlFileData(): array
    {
        return [
            [
                'tester\report-name.html',
                'C:\Some\Data\Path',
                [
                    'backtestReportName' => 'report-name.html',
                ],
                true,
            ],
            [
                'C:\Some\Data\Path\tester\report-name.html',
                'C:\Some\Data\Path',
                [
                    'backtestReportName' => 'report-name.html',
                ],
                false,
            ],
        ];
    }

    public function getGetExpertAdvisorConfigFileData(): array
    {
        return [
            [
                'tester\Alfa.ini',
                'C:\Some\Data\Path',
                'Alfa',
                true,
            ],
            [
                'C:\Some\Data\Path\tester\Beta.ini',
                'C:\Some\Data\Path',
                'Beta',
                false,
            ],
            [
                'tester\Gamma.ini',
                'C:\Some\Data\Path',
                'Gamma',
                true,
            ],
        ];
    }

    public function getGetExpertAdvisorInputsData(): array
    {
        return [
            [
                [
                    'foo' => 'bar',
                ],
                [
                    'something' => 'foo',
                ],
                [
                    'something' => 'bar',
                ],
            ],
            [
                [
                    'AlfaParameter' => 'α',
                    'BetaArgument'  => 'β',
                    'GammaInput'    => 'γ',
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
                    'config'  => [1, 2, 3],
                    'test'    => [4, 5, 6],
                ],
            ],
        ];
    }

    public function getGetRelativePathData(): array
    {
        return [
            [
                'This\Relative\Path',
                'C:\This\Folder\Contains\This\Relative\Path',
                'C:\This\Folder\Contains',
            ],
            [
                'unix/is/better/than/windows',
                '/why/unix/is/better/than/windows',
                '/why',
            ],
        ];
    }

    public function getGetTerminalConfigFileData(): array
    {
        return [
            [
                'tester\tester.ini',
                'C:\Some\Data\Path\1234567890abcdef',
                true,
            ],
            [
                'C:\Some\Data\Path\1234567890abcdef\tester\tester.ini',
                'C:\Some\Data\Path\1234567890abcdef',
                false,
            ],
        ];
    }

    /**
     * @dataProvider getGetBacktestReportHtmlFileData
     */
    public function testGetBacktestReportHtmlFile(string $expected, string $terminalPath, array $currentBacktestSettings, bool $relative): void
    {
        self::assertSame($expected, ConfigHelper::getBacktestReportHtmlFile($terminalPath, $currentBacktestSettings, $relative));
    }

    /**
     * @dataProvider getGetExpertAdvisorConfigFileData
     */
    public function testGetExpertAdvisorConfigFile(string $expected, string $terminalPath, string $expertAdvisorName, bool $relative): void
    {
        self::assertSame($expected, ConfigHelper::getExpertAdvisorConfigFile($terminalPath, $expertAdvisorName, $relative));
    }

    /**
     * @dataProvider getGetExpertAdvisorInputsData
     */
    public function testGetExpertAdvisorInputs(array $expected, array $alias, array $currentBacktestSettings): void
    {
        $expertAdvisor = $this->createMock(ExpertAdvisorInterface::class);
        $expertAdvisor->expects(self::once())->method('getAlias')->willReturn($alias);
        $expertAdvisor->expects(self::once())->method('getCurrentBacktestSettings')->willReturn($currentBacktestSettings);

        self::assertSame($expected, ConfigHelper::getExpertAdvisorInputs($expertAdvisor));
    }

    /**
     * @dataProvider getGetRelativePathData
     */
    public function testGetRelativePath(string $expected, string $fullPath, string $relativePath): void
    {
        self::assertSame($expected, ConfigHelper::getRelativePath($fullPath, $relativePath));
    }

    /**
     * @dataProvider getGetTerminalConfigFileData
     */
    public function testGetTerminalConfigFile(string $expected, string $terminalPath, bool $relative): void
    {
        self::assertSame($expected, ConfigHelper::getTerminalConfigFile($terminalPath, $relative));
    }
}
