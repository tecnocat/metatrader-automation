<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\Annotation\Listen;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\EventSubscriber\AbstractEventSubscriber;

/**
 * @Subscriber
 */
class AnnotationsCompilerPassTestSubscriber extends AbstractEventSubscriber
{
    /**
     * @Listen("annotations.compiler.pass.test")
     */
    public function onAnnotationsCompilerPassTestEvent(AnnotationsCompilerPassTestEvent $event): void
    {
        $event->addError('error: ' . $event->getTest());
    }
}
