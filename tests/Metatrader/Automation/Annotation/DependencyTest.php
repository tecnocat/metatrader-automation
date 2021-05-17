<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\Annotation;

use App\Metatrader\Automation\Annotation\Dependency;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DependencyTest extends WebTestCase
{
    public function testAnnotationExists(): void
    {
        static::assertTrue(class_exists(Dependency::class));
    }
}
