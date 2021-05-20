<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Subscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Subscriber
 */
class AnnotationsCompilerPassTestPropertyExceptionSubscriber
{
    /**
     * @Dependency
     */
    private EventDispatcherInterface $eventDispatcher;
}
