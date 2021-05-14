<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Interfaces;

interface NamespaceInterface
{
    public static function getNamespace(): string;
}
