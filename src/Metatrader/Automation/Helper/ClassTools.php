<?php

namespace App\Metatrader\Automation\Helper;

use DateTime;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use ReflectionType;

/**
 * Class ClassTools
 *
 * @package App\Metatrader\Automation\Helper
 */
class ClassTools
{
    /**
     * @param string $name
     *
     * @return string
     */
    public static function getCamelCaseDashed(string $name): string
    {
        return self::getCamelCaseGlue($name, '-');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getCamelCaseDotted(string $name): string
    {
        return self::getCamelCaseGlue($name, '.');
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function getCamelCaseUnderscore(string $name): string
    {
        return self::getCamelCaseGlue($name, '_');
    }

    /**
     * @param $object
     *
     * @return string
     */
    public static function getClassNameDashed($object): string
    {
        return self::getClassNameGlue($object, '-');
    }

    /**
     * @param $object
     *
     * @return string
     */
    public static function getClassNameDotted($object): string
    {
        return self::getClassNameGlue($object, '.');
    }

    /**
     * @param $object
     *
     * @return string
     */
    public static function getClassNameUnderscore($object): string
    {
        return self::getClassNameGlue($object, '_');
    }

    /**
     * @param        $object
     * @param string $name
     *
     * @return mixed
     */
    public static function getPropertyValue($object, string $name)
    {
        $property = self::getAccessibleProperty($object, $name);

        return $property->getValue($object);
    }

    /**
     * @param        $object
     * @param string $name
     *
     * @return bool
     * @throws ReflectionException
     */
    public static function hasProperty($object, string $name): bool
    {
        return self::getReflection($object)->hasProperty($name);
    }

    /**
     * @param        $object
     * @param string $name
     * @param        $value
     *
     * @throws ReflectionException
     */
    public static function setPropertyValue($object, string $name, $value): void
    {
        $property = self::getAccessibleProperty($object, $name);
        $property->setValue($object, self::castToType($object, $name, $value));
    }

    /**
     * @param        $object
     * @param string $name
     * @param        $value
     *
     * @return bool|DateTime|float|int|string
     * @throws ReflectionException
     */
    private static function castToType($object, string $name, $value)
    {
        $propertyType = self::getPropertyType($object, $name);

        switch ($propertyType->getName())
        {
            case 'bool':
                return (bool) $value;

            case 'DateTime':
                return new DateTime($value);

            case 'float':
                return (float) $value;

            case 'int':
                return (int) $value;

            case 'string':
                return (string) $value;

            default:
                throw new Exception('Unsupported this value type: ' . $propertyType->getName());
        }
    }

    /**
     * @param string $name
     * @param string $glue
     *
     * @return string
     */
    private static function getCamelCaseGlue(string $name, string $glue): string
    {
        return ltrim(mb_strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', $glue . '$0', $name)), $glue);
    }

    /**
     * @param        $object
     * @param string $glue
     *
     * @return string
     * @throws ReflectionException
     */
    private static function getClassNameGlue($object, string $glue): string
    {
        $reflection = self::getReflection($object);

        return self::getCamelCaseGlue($reflection->getShortName(), $glue);
    }

    /**
     * @param $object
     *
     * @return ReflectionClass
     * @throws ReflectionException
     */
    private static function getReflection($object): ReflectionClass
    {
        return $object instanceof ReflectionClass ? $object : new ReflectionClass($object);
    }

    /**
     * @param        $object
     * @param string $name
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    private static function getAccessibleProperty($object, string $name): ReflectionProperty
    {
        $property = self::getProperty($object, $name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * @param $object
     *
     * @return array
     * @throws ReflectionException
     */
    private static function getProperties($object): array
    {
        return self::getReflection($object)->getProperties();
    }

    /**
     * @param        $object
     * @param string $name
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    private static function getProperty($object, string $name): ReflectionProperty
    {
        return self::getReflection($object)->getProperty($name);
    }

    /**
     * @param        $object
     * @param string $name
     *
     * @return ReflectionType
     * @throws ReflectionException
     */
    private static function getPropertyType($object, string $name): ReflectionType
    {
        return self::getProperty($object, $name)->getType();
    }
}
