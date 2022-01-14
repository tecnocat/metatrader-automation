<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\CartesianHelper;
use PHPUnit\Framework\TestCase;

class CartesianHelperTest extends TestCase
{
    public function getAsArrayData(): array
    {
        return [
            [
                [
                    [1],
                    [2],
                ],
                [
                    range(1, 2),
                ],
            ],
            [
                [
                    [1, 1],
                    [1, 2],
                    [2, 1],
                    [2, 2],
                ],
                [
                    range(1, 2),
                    range(1, 2),
                ],
            ],
            [
                [
                    [1, 1, 1],
                    [1, 1, 2],
                    [1, 1, 3],
                    [1, 2, 1],
                    [1, 2, 2],
                    [1, 2, 3],
                    [1, 3, 1],
                    [1, 3, 2],
                    [1, 3, 3],
                    [2, 1, 1],
                    [2, 1, 2],
                    [2, 1, 3],
                    [2, 2, 1],
                    [2, 2, 2],
                    [2, 2, 3],
                    [2, 3, 1],
                    [2, 3, 2],
                    [2, 3, 3],
                    [3, 1, 1],
                    [3, 1, 2],
                    [3, 1, 3],
                    [3, 2, 1],
                    [3, 2, 2],
                    [3, 2, 3],
                    [3, 3, 1],
                    [3, 3, 2],
                    [3, 3, 3],
                ],
                [
                    range(1, 3),
                    range(1, 3),
                    range(1, 3),
                ],
            ],
        ];
    }

    public function getCountData(): array
    {
        return [
            [
                2,
                [
                    range(1, 2),
                ],
            ],
            [
                2 * 2,
                [
                    range(1, 2),
                    range(1, 2),
                ],
            ],
            [
                3 * 3 * 3,
                [
                    range(1, 3),
                    range(1, 3),
                    range(1, 3),
                ],
            ],
        ];
    }

    public function get__constructData(): array
    {
        return [
            [
                [
                    range(1, 2),
                ],
                [
                    [1],
                    [2],
                ],
                2,
            ],
            [
                [
                    range(1, 2),
                    range(1, 2),
                ],
                [
                    [1, 1],
                    [1, 2],
                    [2, 1],
                    [2, 2],
                ],
                2 * 2,
            ],
            [
                [
                    range(1, 3),
                    range(1, 3),
                    range(1, 3),
                ],
                [
                    [1, 1, 1],
                    [1, 1, 2],
                    [1, 1, 3],
                    [1, 2, 1],
                    [1, 2, 2],
                    [1, 2, 3],
                    [1, 3, 1],
                    [1, 3, 2],
                    [1, 3, 3],
                    [2, 1, 1],
                    [2, 1, 2],
                    [2, 1, 3],
                    [2, 2, 1],
                    [2, 2, 2],
                    [2, 2, 3],
                    [2, 3, 1],
                    [2, 3, 2],
                    [2, 3, 3],
                    [3, 1, 1],
                    [3, 1, 2],
                    [3, 1, 3],
                    [3, 2, 1],
                    [3, 2, 2],
                    [3, 2, 3],
                    [3, 3, 1],
                    [3, 3, 2],
                    [3, 3, 3],
                ],
                3 * 3 * 3,
            ],
        ];
    }

    public function testAllCoverage(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $cartesianHelper = new CartesianHelper(['string']);
        $cartesianHelper->asArray();
    }

    /**
     * @dataProvider getAsArrayData
     */
    public function testAsArray(array $expected, array $set): void
    {
        $cartesianHelper = new CartesianHelper($set);

        self::assertSame($expected, $cartesianHelper->asArray());
    }

    /**
     * @dataProvider getCountData
     */
    public function testCount(int $expected, array $set): void
    {
        $cartesianHelper = new CartesianHelper($set);

        self::assertSame($expected, $cartesianHelper->count());
    }

    public function testGetIterator(): void
    {
        $cartesianHelper = new CartesianHelper([]);
        self::assertInstanceOf(\Generator::class, $cartesianHelper->getIterator());
    }

    /**
     * @dataProvider get__constructData
     */
    public function testConstruct(array $set, array $array, int $count): void
    {
        $cartesianHelper = new CartesianHelper($set);

        self::assertSame($array, $cartesianHelper->asArray());
        self::assertSame($count, $cartesianHelper->count());
    }
}
