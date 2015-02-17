<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Helper;

class CacheHelper
{
    private static array $cache = [];

    /**
     * @return null|mixed
     */
    public static function getCache(string $group, string $key)
    {
        return self::$cache[$group][md5($key)] ?? null;
    }

    /**
     * @param mixed $data
     */
    public static function setCache(string $group, string $key, $data): void
    {
        if (!isset(self::$cache[$group]))
        {
            self::$cache[$group] = [];
        }

        self::$cache[$group][md5($key)] = $data;
    }
}
