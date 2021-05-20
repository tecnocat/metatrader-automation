<?php

declare(strict_types=1);

$baseDir = dirname(__DIR__, 2);

return [
    'App\Tests\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPassTestSubscriber'                  => $baseDir . '/AnnotationsCompilerPassTestSubscriber.php',
    'App\Tests\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPassTestMethodCallSubscriber'        => $baseDir . '/AnnotationsCompilerPassTestMethodCallSubscriber.php',
    'App\Tests\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPassTestPropertySubscriber'          => $baseDir . '/AnnotationsCompilerPassTestPropertySubscriber.php',
    'App\Tests\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPassTestException'                   => $baseDir . '/AnnotationsCompilerPassTestException.php',
    'App\Tests\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPassTestPropertyExceptionSubscriber' => $baseDir . '/AnnotationsCompilerPassTestPropertyExceptionSubscriber.php',
];
