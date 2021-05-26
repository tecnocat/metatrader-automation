<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Helper;

use App\Metatrader\Automation\Helper\ClassHelper;
use PHPUnit\Framework\TestCase;

class ClassHelperTest extends TestCase
{
    public function getToCamelCaseData(): array
    {
        return [
            [
                'std_class',
                'StdClass',
            ],
            [
                'Std_Class',
                'StdClass',
            ],
            [
                'std-class',
                'StdClass',
            ],
            [
                'std.class',
                'StdClass',
            ],
            [
                'std:class',
                'StdClass',
            ],
        ];
    }

    public function getToColonData(): array
    {
        return [
            [
                'StdClass',
                'std:class',
            ],
            [
                'TESTStdClass',
                'test:std:class',
            ],
        ];
    }

    public function getToDashData(): array
    {
        return [
            [
                'StdClass',
                'std-class',
            ],
            [
                'TESTStdClass',
                'test-std-class',
            ],
        ];
    }

    public function getToDotData(): array
    {
        return [
            [
                'StdClass',
                'std.class',
            ],
            [
                'TESTStdClass',
                'test.std.class',
            ],
        ];
    }

    public function getToUnderscoreData(): array
    {
        return [
            [
                'StdClass',
                'std_class',
            ],
            [
                'TESTStdClass',
                'test_std_class',
            ],
        ];
    }

    public function testAllCoverage(): void
    {
        $dateTime                    = new \DateTime('now');
        $classHelperTestObjectSource = new ClassHelperTestObjectSource($dateTime);
        $classHelperTestObjectTarget = new ClassHelperTestObjectTarget($dateTime);
        ClassHelper::copyFields($classHelperTestObjectSource, $classHelperTestObjectTarget);
        self::assertClassHasAttribute('onlyInSource', get_class($classHelperTestObjectSource));
        self::assertClassHasAttribute('onlyInTarget', get_class($classHelperTestObjectTarget));
        self::assertClassNotHasAttribute('onlyInTarget', get_class($classHelperTestObjectSource));
        self::assertClassNotHasAttribute('onlyInSource', get_class($classHelperTestObjectTarget));
    }

    public function testCopyFields(): void
    {
        $classHelperTestObjectSource = new ClassHelperTestObject();
        $classHelperTestObjectTarget = new ClassHelperTestObject();
        $classHelperTestObjectSource->setCode('1234ABC');
        $classHelperTestObjectSource->setId(rand(1, 9999));
        $classHelperTestObjectSource->setName('test');
        ClassHelper::copyFields($classHelperTestObjectSource, $classHelperTestObjectTarget);
        $sourcePropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectSource);
        $targetPropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectTarget);
        self::assertSame($sourcePropertyValues, $targetPropertyValues);
    }

    public function testGetClassName(): void
    {
        $entityMock = $this->createMock(ClassHelper::class);
        $expected   = basename(get_class($entityMock));

        self::assertSame($expected, ClassHelper::getClassName($entityMock));
    }

    public function testGetClassNameCamelCase(): void
    {
        self::assertSame('StdClass', ClassHelper::getClassNameCamelCase(new \stdClass()));
    }

    public function testGetClassNameColon(): void
    {
        self::assertSame('std:class', ClassHelper::getClassNameColon(new \stdClass()));
    }

    public function testGetClassNameDash(): void
    {
        self::assertSame('std-class', ClassHelper::getClassNameDash(new \stdClass()));
    }

    public function testGetClassNameDot(): void
    {
        self::assertSame('std.class', ClassHelper::getClassNameDot(new \stdClass()));
    }

    public function testGetClassNameUnderscore(): void
    {
        self::assertSame('std_class', ClassHelper::getClassNameUnderscore(new \stdClass()));
    }

    public function testGetProperties(): void
    {
        $classHelperTestObject = new ClassHelperTestObject();
        $expected              = [
            'code',
            'id',
            'name',
        ];
        $callback              = function ($property)
        {
            return $property->getName();
        };

        self::assertSame($expected, array_map($callback, ClassHelper::getProperties($classHelperTestObject)));
    }

    public function testGetPropertyValue(): void
    {
        $code   = '1234ABC';
        $id     = rand(1, 9999);
        $name   = 'test';
        $object = $this->getClassHelperTestObject($code, $id, $name);
        self::assertSame($id, ClassHelper::getPropertyValue($object, 'id'));
        self::assertSame($name, ClassHelper::getPropertyValue($object, 'name'));
        self::assertSame($code, ClassHelper::getPropertyValue($object, 'code'));
    }

    public function testGetPropertyValues(): void
    {
        $code   = '1234ABC';
        $id     = rand(1, 9999);
        $name   = 'test';
        $values = [
            'code' => $code,
            'id'   => $id,
            'name' => $name,
        ];
        $object = $this->getClassHelperTestObject($code, $id, $name);
        self::assertSame($values, ClassHelper::getPropertyValues($object));
    }

    public function testHasProperty(): void
    {
        $classHelperTestObject = new ClassHelperTestObject();
        self::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'code'));
        self::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'id'));
        self::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'name'));
        self::assertFalse(ClassHelper::hasProperty($classHelperTestObject, 'test'));
    }

    public function testSetPropertyValue(): void
    {
        $code                        = '1234ABC';
        $id                          = rand(1, 9999);
        $name                        = 'test';
        $classHelperTestObjectSource = $this->getClassHelperTestObject($code, $id, $name);
        $classHelperTestObjectTarget = new ClassHelperTestObject();
        ClassHelper::setPropertyValue($classHelperTestObjectTarget, 'code', $code);
        ClassHelper::setPropertyValue($classHelperTestObjectTarget, 'id', $id);
        ClassHelper::setPropertyValue($classHelperTestObjectTarget, 'name', $name);
        $sourcePropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectSource);
        $targetPropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectTarget);
        self::assertSame($sourcePropertyValues, $targetPropertyValues);
    }

    public function testSetPropertyValues(): void
    {
        $code                        = '1234ABC';
        $id                          = rand(1, 9999);
        $name                        = 'test';
        $values                      = [
            'code' => $code,
            'id'   => $id,
            'name' => $name,
        ];
        $classHelperTestObjectSource = $this->getClassHelperTestObject($code, $id, $name);
        $classHelperTestObjectTarget = new ClassHelperTestObject();
        ClassHelper::setPropertyValues($classHelperTestObjectTarget, $values);
        $sourcePropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectSource);
        $targetPropertyValues = ClassHelper::getPropertyValues($classHelperTestObjectTarget);
        self::assertSame($sourcePropertyValues, $targetPropertyValues);
    }

    /**
     * @dataProvider getToCamelCaseData
     */
    public function testToCamelCase(string $class, string $expected): void
    {
        self::assertSame($expected, ClassHelper::toCamelCase($class));
    }

    /**
     * @dataProvider getToColonData
     */
    public function testToColon(string $class, string $expected): void
    {
        self::assertSame($expected, ClassHelper::toColon($class));
    }

    /**
     * @dataProvider getToDashData
     */
    public function testToDash(string $class, string $expected): void
    {
        self::assertSame($expected, ClassHelper::toDash($class));
    }

    /**
     * @dataProvider getToDotData
     */
    public function testToDot(string $class, string $expected): void
    {
        self::assertSame($expected, ClassHelper::toDot($class));
    }

    /**
     * @dataProvider getToUnderscoreData
     */
    public function testToUnderscore(string $class, string $expected): void
    {
        self::assertSame($expected, ClassHelper::toUnderscore($class));
    }

    private function getClassHelperTestObject(string $code, int $id, string $name): ClassHelperTestObject
    {
        $classHelperTestObject = new ClassHelperTestObject();
        $classHelperTestObject->setCode($code);
        $classHelperTestObject->setId($id);
        $classHelperTestObject->setName($name);

        return $classHelperTestObject;
    }
}
