<?php

declare(strict_types=1);

namespace App\Tests\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\DependencyInjection\Compiler\AnnotationsCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AnnotationsCompilerPassTest extends TestCase
{
    public function getProcessData(): array
    {
        return [
            [
                null,
                null,
                null,
            ],
            [
                'setEventDispatcher',
                null,
                EventDispatcherInterface::class,
            ],
            [
                null,
                'eventDispatcher',
                EventDispatcherInterface::class,
            ],
        ];
    }

    /**
     * @dataProvider getProcessData
     *
     * @param ?string $methodCall
     * @param ?string $property
     * @param ?string $classReference
     */
    public function testProcess(?string $methodCall, ?string $property, ?string $classReference): void
    {
        $definition = $this->createMock(Definition::class);
        $definition->expects(static::exactly(3))->method('addTag')->with(
            'kernel.event_listener',
            [
                'event'  => 'annotations.compiler.pass.test',
                'method' => 'onAnnotationsCompilerPassTestEvent',
            ]
        )->willReturn($definition)
        ;

        if (null !== $classReference)
        {
            $reference = new Reference($classReference);

            if (null !== $methodCall)
            {
                $definition->expects(static::exactly(1))->method('addMethodCall')->with($methodCall, [$reference])->willReturn($definition);
            }
            elseif (null !== $property)
            {
                $definition->expects(static::exactly(2))->method('setProperty')->with($property, $reference)->willReturn($definition);
            }
        }

        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(static::exactly(2))->method('getParameter')->withConsecutive(
            [
                static::equalTo('kernel.project_dir'),
            ],
            [
                static::equalTo('kernel.environment'),
            ]
        )->willReturnOnConsecutiveCalls(
            __DIR__,
            'test',
        )
        ;
        $container->expects(static::exactly(5))->method('getDefinition')->withConsecutive(
            [
                static::equalTo(AnnotationsCompilerPassTestSubscriber::class),
            ],
            [
                static::equalTo(AnnotationsCompilerPassTestMethodCallSubscriber::class),
            ],
            [
                static::equalTo(AnnotationsCompilerPassTestPropertySubscriber::class),
            ],
            [
                static::equalTo(AnnotationsCompilerPassTestException::class),
            ],
            [
                static::equalTo(AnnotationsCompilerPassTestPropertyExceptionSubscriber::class),
            ],
        )->willReturnOnConsecutiveCalls(
            $definition,
            $definition,
            $definition,
            static::throwException(new ServiceNotFoundException(AnnotationsCompilerPassTestException::class)),
            $definition,
        )
        ;
        static::expectException(\RuntimeException::class);

        $annotationsCompilerPass = new AnnotationsCompilerPass();
        $annotationsCompilerPass->process($container);
    }
}
