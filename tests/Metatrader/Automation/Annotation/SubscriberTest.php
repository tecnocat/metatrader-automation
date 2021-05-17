<?php

namespace App\Tests\Metatrader\Automation\Annotation;

use App\Metatrader\Automation\Annotation\Subscriber;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubscriberTest extends WebTestCase
{
    public function testAnnotationExists(): void
    {
        static::assertTrue(class_exists(Subscriber::class));
    }
}
