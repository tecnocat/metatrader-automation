<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Before
{
    /**
     * @Required
     */
    public string $method;
}
