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
        $definition->expects(self::exactly(3))->method('addTag')->with(
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
                $definition->expects(self::exactly(1))->method('addMethodCall')->with($methodCall, [$reference])->willReturn($definition);
            }
            elseif (null !== $property)
            {
                $definition->expects(self::exactly(2))->method('setProperty')->with($property, $reference)->willReturn($definition);
            }
        }

        $container = $this->createMock(ContainerBuilder::class);
        $container->expects(self::exactly(2))->method('getParameter')->withConsecutive(
            [
                self::equalTo('kernel.project_dir'),
            ],
            [
                self::equalTo('kernel.environment'),
            ]
        )->willReturnOnConsecutiveCalls(
            __DIR__,
            'test',
        )
        ;
        $container->expects(self::exactly(5))->method('getDefinition')->withConsecutive(
            [
                self::equalTo(AnnotationsCompilerPassTestSubscriber::class),
            ],
            [
                self::equalTo(AnnotationsCompilerPassTestMethodCallSubscriber::class),
            ],
            [
                self::equalTo(AnnotationsCompilerPassTestPropertySubscriber::class),
            ],
            [
                self::equalTo(AnnotationsCompilerPassTestException::class),
            ],
            [
                self::equalTo(AnnotationsCompilerPassTestPropertyExceptionSubscriber::class),
            ],
        )->willReturnOnConsecutiveCalls(
            $definition,
            $definition,
            $definition,
            self::throwException(new ServiceNotFoundException(AnnotationsCompilerPassTestException::class)),
            $definition,
        )
        ;
        self::expectException(\RuntimeException::class);

        $annotationsCompilerPass = new AnnotationsCompilerPass();
        $annotationsCompilerPass->process($container);
    }
}
