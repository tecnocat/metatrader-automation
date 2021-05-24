<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class ClassHelper
{
    private static array $cache = [];

    public static function copyFields(object $source, object $target): void
    {
        foreach (self::getProperties($source) as $property)
        {
            $field = $property->getName();

            if (!self::hasProperty($target, $field))
            {
                continue;
            }

            $value = self::getPropertyValue($source, $field);

            if (null === $value)
            {
                continue;
            }

            self::setPropertyValue($target, $field, $value);
        }
    }

    /**
     * @param object|string $class
     */
    public static function getClassName($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getReflection($class)->getShortName());
    }

    /**
     * @param object|string $class
     */
    public static function getClassNameCamelCase($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::toCamelCase(self::getClassName($class)));
    }

    /**
     * @param object|string $class
     */
    public static function getClassNameColon($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getClassNameGlue($class, ':'));
    }

    /**
     * @param object|string $class
     */
    public static function getClassNameDash($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getClassNameGlue($class, '-'));
    }

    /**
     * @param object|string $class
     */
    public static function getClassNameDot($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getClassNameGlue($class, '.'));
    }

    /**
     * @param object|string $class
     */
    public static function getClassNameUnderscore($class): string
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getClassNameGlue($class, '_'));
    }

    /**
     * @param object|string $class
     */
    public static function getProperties($class): array
    {
        return self::getCache(__FUNCTION__, $class) ?: self::setCache(__FUNCTION__, $class, self::getReflection($class)->getProperties());
    }

    public static function getPropertyType($class, string $name): string
    {
        $cacheKey = (is_object($class) ? get_class($class) : $class) . '->' . $name;

        if ($cache = self::getCache(__FUNCTION__, $cacheKey))
        {
            return $cache;
        }

        $property = self::getProperty($class, $name);

        if ($property->hasType())
        {
            return self::setCache(__FUNCTION__, $cacheKey, $property->getType()->getName());
        }

        return self::setCache(__FUNCTION__, $cacheKey, 'string');
    }

    public static function getPropertyValue(object $object, string $name)
    {
        $property = self::getAccessibleProperty($object, $name);

        return $property->getValue($object);
    }

    public static function getPropertyValues(object $object): array
    {
        $result = [];

        foreach (self::getProperties($object) as $property)
        {
            $result[$property->getName()] = self::getPropertyValue($object, $property->getName());
        }

        return $result;
    }

    /**
     * @param object|string $class
     */
    public static function hasProperty($class, string $name): bool
    {
        $cacheKey = (is_object($class) ? get_class($class) : $class) . '->' . $name;

        return self::getCache(__FUNCTION__, $cacheKey) ?: self::setCache(__FUNCTION__, $cacheKey, self::getReflection($class)->hasProperty($name));
    }

    /**
     * @param bool|\DateTime|float|int|mixed|string $value
     */
    public static function setPropertyValue(object $object, string $name, $value): void
    {
        $property = self::getAccessibleProperty($object, $name);
        $property->setValue($object, self::castToType($object, $name, $value));
    }

    public static function setPropertyValues(object $object, array $parameters): void
    {
        foreach ($parameters as $field => $value)
        {
            if (self::hasProperty($object, $field))
            {
                self::setPropertyValue($object, $field, $value);
            }
        }
    }

    public static function toCamelCase(string $name): string
    {
        return self::getCache(__FUNCTION__, $name) ?: self::setCache(__FUNCTION__, $name, str_replace(' ', '', ucwords(str_replace(['_', '-', '.', ':'], ' ', $name))));
    }

    public static function toColon(string $name): string
    {
        return self::getCache(__FUNCTION__, $name) ?: self::setCache(__FUNCTION__, $name, self::formulae(self::toCamelCase($name), ':'));
    }

    public static function toDash(string $name): string
    {
        return self::getCache(__FUNCTION__, $name) ?: self::setCache(__FUNCTION__, $name, self::formulae(self::toCamelCase($name), '-'));
    }

    public static function toDot(string $name): string
    {
        return self::getCache(__FUNCTION__, $name) ?: self::setCache(__FUNCTION__, $name, self::formulae(self::toCamelCase($name), '.'));
    }

    public static function toUnderscore(string $name): string
    {
        return self::getCache(__FUNCTION__, $name) ?: self::setCache(__FUNCTION__, $name, self::formulae(self::toCamelCase($name), '_'));
    }

    /**
     * @param bool|\DateTime|float|int|mixed|string $value
     *
     * @return bool|\DateTime|float|int|mixed|string
     */
    private static function castToType($class, string $name, $value)
    {
        switch (self::getPropertyType($class, $name))
        {
            case 'bool':
                return (bool) $value;

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

    private static function formulae(string $name, string $glue): string
    {
        return ltrim(mb_strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', $glue . '$0', $name)), $glue);
    }

    /**
     * @param object|string $class
     */
    private static function getAccessibleProperty($class, string $name): \ReflectionProperty
    {
        $property = self::getProperty($class, $name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * @param object|string $class
     */
    private static function getCache(string $index, $class)
    {
        return self::$cache[$index][is_object($class) ? get_class($class) : $class] ?? null;
    }

    private static function getClassNameGlue($class, string $glue): string
    {
        return self::formulae(self::getClassName($class), $glue);
    }

    /**
     * @param object|string $class
     */
    private static function getProperty($class, string $name): \ReflectionProperty
    {
        return self::getReflection($class)->getProperty($name);
    }

    /**
     * @param object|string $class
     */
    private static function getReflection($class): \ReflectionClass
    {
        return new \ReflectionClass($class);
    }

    /**
     * @param object|string $class
     */
    private static function setCache(string $index, $class, $value)
    {
        return self::$cache[$index][is_object($class) ? get_class($class) : $class] = $value;
    }
}
