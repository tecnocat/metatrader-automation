<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class ClassTools
{
    public static function getCamelCaseDashed(string $name): string
    {
        return self::getCamelCaseGlue($name, '-');
    }

    public static function getCamelCaseDotted(string $name): string
    {
        return self::getCamelCaseGlue($name, '.');
    }

    public static function getCamelCaseUnderscore(string $name): string
    {
        return self::getCamelCaseGlue($name, '_');
    }

    public static function getClassNameDashed(object $object): string
    {
        return self::getClassNameGlue($object, '-');
    }

    public static function getClassNameDotted(object $object): string
    {
        return self::getClassNameGlue($object, '.');
    }

    public static function getClassNameUnderscore(object $object): string
    {
        return self::getClassNameGlue($object, '_');
    }

    public static function getPropertyValue(object $object, string $name)
    {
        $property = self::getAccessibleProperty($object, $name);

        return $property->getValue($object);
    }

    public static function hasProperty(object $object, string $name): bool
    {
        return self::getReflection($object)->hasProperty($name);
    }

    /**
     * @param bool|\DateTime|float|int|mixed|string $value
     */
    public static function setPropertyValue(object $object, string $name, $value): void
    {
        $property = self::getAccessibleProperty($object, $name);
        $property->setValue($object, self::castToType($object, $name, $value));
    }

    /**
     * @param bool|\DateTime|float|int|mixed|string $value
     *
     * @return bool|\DateTime|float|int|mixed|string
     */
    private static function castToType(object $object, string $name, $value)
    {
        switch (self::getPropertyType($object, $name))
        {
            case 'bool':
                return (bool) $value;

            case 'DateTime':
                try
                {
                    return new \DateTime($value);
                }
                catch (\Exception $e)
                {
                    return $value;
                }

            case 'float':
                return (float) $value;

            case 'int':
                return (int) $value;

            case 'string':
                return (string) $value;

            default:
                return $value;
        }
    }

    private static function getAccessibleProperty(object $object, string $name): \ReflectionProperty
    {
        $property = self::getProperty($object, $name);
        $property->setAccessible(true);

        return $property;
    }

    private static function getCamelCaseGlue(string $name, string $glue): string
    {
        return ltrim(mb_strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', $glue . '$0', $name)), $glue);
    }

    private static function getClassNameGlue(object $object, string $glue): string
    {
        $reflection = self::getReflection($object);

        return self::getCamelCaseGlue($reflection->getShortName(), $glue);
    }

    private static function getProperty(object $object, string $name): \ReflectionProperty
    {
        return self::getReflection($object)->getProperty($name);
    }

    private static function getPropertyType(object $object, string $name): string
    {
        $property = self::getProperty($object, $name);

        if ($property->hasType())
        {
            return (string) $property->getType();
        }

        return 'string';
    }

    private static function getReflection(object $object): \ReflectionClass
    {
        return $object instanceof \ReflectionClass ? $object : new \ReflectionClass($object);
    }
}
