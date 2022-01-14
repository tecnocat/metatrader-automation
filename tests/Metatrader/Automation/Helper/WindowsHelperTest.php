<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\WindowsHelper;
use PHPUnit\Framework\TestCase;

class WindowsHelperTest extends TestCase
{
    public function testGetNumberOfCores(): void
    {
        $wmic = popen('wmic cpu get NumberOfLogicalProcessors', 'rb');
        fgets($wmic);
        $expected = intval(fgets($wmic));
        pclose($wmic);

        self::assertSame($expected, WindowsHelper::getNumberOfCores());
    }

    public function testGetTerminalsRunning(): void
    {
        self::assertSame([], WindowsHelper::getTerminalsRunning());
    }
}
