<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\DependencyInjection\Compiler;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Listen;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Helper\ClassHelper;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

class AnnotationsCompilerPass implements CompilerPassInterface
{
    private AnnotationReader $annotationReader;

    public function process(ContainerBuilder $container): void
    {
        $this->annotationReader = new AnnotationReader();

        foreach ($this->getClassMap($container) as $className => $classFile)
        {
            if (!realpath($classFile))
            {
                throw new \RuntimeException("Missing class $className in composer autoload, run composer dump-autoload to fix that."); // @codeCoverageIgnore
            }

            try
            {
                $reflectionClass = new \ReflectionClass($className);
                $definition      = $container->getDefinition($className);
            }
            catch (\ReflectionException | ServiceNotFoundException $exception)
            {
                continue;
            }

            $this->findSubscribers($reflectionClass, $definition);
            $this->findDependencies($reflectionClass, $definition);
        }
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    protected function findDependencies(\ReflectionClass $reflectionClass, Definition $definition): void
    {
        foreach ($reflectionClass->getProperties() as $property)
        {
            if ($this->annotationReader->getPropertyAnnotation($property, Dependency::class))
            {
                $this->injectDependency($definition, $reflectionClass, $property);
            }
        }
    }

    protected function findSubscribers(\ReflectionClass $reflectionClass, Definition $definition): void
    {
        if ($this->annotationReader->getClassAnnotation($reflectionClass, Subscriber::class))
        {
            foreach ($reflectionClass->getMethods() as $method)
            {
                if ('Event' === mb_substr($method->getName(), -5))
                {
                    $this->registerEventListener($definition, $method);
                }
            }
        }
    }

    protected function getClassMap(ContainerBuilder $container): array
    {
        $projectDir  = $container->getParameter('kernel.project_dir');
        $environment = $container->getParameter('kernel.environment');

        return array_filter(
            require $projectDir . '/vendor/composer/autoload_classmap.php',
            function ($class) use ($environment)
            {
                return 0 === mb_strpos($class, 'test' === $environment ? 'App\Tests\Metatrader\Automation' : 'App\Metatrader\Automation');
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    protected function injectDependency(Definition $definition, \ReflectionClass $reflectionClass, \ReflectionProperty $property): void
    {
        $dependency = $property->getType()->getName();
        $reference  = new Reference($dependency);

        if ($property->isPublic())
        {
            $definition->setProperty($property->getName(), $reference);

            return;
        }

        $methodCall = 'set' . str_replace('Interface', '', ClassHelper::getClassName($dependency));

        if ($reflectionClass->hasMethod($methodCall))
        {
            $definition->addMethodCall($methodCall, [$reference]);

            return;
        }

        throw new \RuntimeException(sprintf('Private property "%s" or missing method "%s" in class "%s"', $property->getName(), $methodCall, $reflectionClass->getName()));
    }

    protected function registerEventListener(Definition $definition, \ReflectionMethod $method): void
    {
        if ($annotation = $this->annotationReader->getMethodAnnotation($method, Listen::class))
        {
            $eventName = $annotation->event;
        }
        else
        {
            $methodParameter = current($method->getParameters());

            if (!$methodParameter->hasType())
            {
                return; // @codeCoverageIgnore
            }

            $eventType = ClassHelper::getClassNameDot($methodParameter->getType()->getName());
            $eventName = str_replace('.event', '', $eventType);
        }

        $definition->addTag(
            'kernel.event_listener',
            [
                'event'  => $eventName,
                'method' => $method->getName(),
            ]
        );
    }
}
