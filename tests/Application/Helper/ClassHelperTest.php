<?php

declare(strict_types=1);

namespace App\Tests\Application\Helper;

use App\Metatrader\Automation\Helper\ClassHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassHelperTest extends WebTestCase
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
        ];
    }

    public function getToDashedData(): array
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
        static::assertClassHasAttribute('onlyInSource', get_class($classHelperTestObjectSource));
        static::assertClassHasAttribute('onlyInTarget', get_class($classHelperTestObjectTarget));
        static::assertClassNotHasAttribute('onlyInTarget', get_class($classHelperTestObjectSource));
        static::assertClassNotHasAttribute('onlyInSource', get_class($classHelperTestObjectTarget));
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
        static::assertSame($sourcePropertyValues, $targetPropertyValues);
    }

    public function testGetClassName(): void
    {
        $entityMock = $this->createMock(ClassHelper::class);
        $expected   = basename(get_class($entityMock));

        static::assertSame($expected, ClassHelper::getClassName($entityMock));
    }

    public function testGetClassNameCamelCase(): void
    {
        static::assertSame('StdClass', ClassHelper::getClassNameCamelCase(new \stdClass()));
    }

    public function testGetClassNameDashed(): void
    {
        static::assertSame('std-class', ClassHelper::getClassNameDashed(new \stdClass()));
    }

    public function testGetClassNameDotted(): void
    {
        static::assertSame('std.class', ClassHelper::getClassNameDotted(new \stdClass()));
    }

    public function testGetClassNameUnderscore(): void
    {
        static::assertSame('std_class', ClassHelper::getClassNameUnderscore(new \stdClass()));
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

        static::assertSame($expected, array_map($callback, ClassHelper::getProperties($classHelperTestObject)));
    }

    public function testGetPropertyValue(): void
    {
        $code   = '1234ABC';
        $id     = rand(1, 9999);
        $name   = 'test';
        $object = $this->getClassHelperTestObject($code, $id, $name);
        static::assertSame($id, ClassHelper::getPropertyValue($object, 'id'));
        static::assertSame($name, ClassHelper::getPropertyValue($object, 'name'));
        static::assertSame($code, ClassHelper::getPropertyValue($object, 'code'));
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
        static::assertSame($values, ClassHelper::getPropertyValues($object));
    }

    public function testHasProperty(): void
    {
        $classHelperTestObject = new ClassHelperTestObject();
        static::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'code'));
        static::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'id'));
        static::assertTrue(ClassHelper::hasProperty($classHelperTestObject, 'name'));
        static::assertFalse(ClassHelper::hasProperty($classHelperTestObject, 'test'));
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
        static::assertSame($sourcePropertyValues, $targetPropertyValues);
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
        static::assertSame($sourcePropertyValues, $targetPropertyValues);
    }

    /**
     * @dataProvider getToCamelCaseData
     */
    public function testToCamelCase(string $class, string $expected): void
    {
        static::assertSame($expected, ClassHelper::toCamelCase($class));
    }

    /**
     * @dataProvider getToDashedData
     */
    public function testToDashed(string $class, string $expected): void
    {
        static::assertSame($expected, ClassHelper::toDashed($class));
    }

    /**
     * @dataProvider getToDotData
     */
    public function testToDot(string $class, string $expected): void
    {
        static::assertSame($expected, ClassHelper::toDot($class));
    }

    /**
     * @dataProvider getToUnderscoreData
     */
    public function testToUnderscore(string $class, string $expected): void
    {
        static::assertSame($expected, ClassHelper::toUnderscore($class));
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
