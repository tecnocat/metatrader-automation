<?php

declare(strict_types=1);

namespace App;

use App\Metatrader\Automation\Annotation\Dependency;
use App\Metatrader\Automation\Annotation\Listen;
use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Helper\ClassHelper;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $this->parseAnnotations($container);
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/services.yaml'))
        {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        }
        elseif (is_file($path = dirname(__DIR__) . '/config/services.php'))
        {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/routes.yaml'))
        {
            $routes->import('../config/routes.yaml');
        }
        elseif (is_file($path = dirname(__DIR__) . '/config/routes.php'))
        {
            (require $path)($routes->withPath($path), $this);
        }
    }

    /**
     * TODO: Make this monster reusable and testable inside a compiler pass
     */
    private function parseAnnotations(ContainerBuilder $container): void
    {
        $annotationReader = new AnnotationReader();
        $classMap         = require __DIR__ . '/../vendor/composer/autoload_classmap.php';

        foreach ($classMap as $className => $classFile)
        {
            if (!$realPath = realpath($classFile))
            {
                throw new \RuntimeException("Missing class $className in composer autoload, run composer dump-autoload to fix that.");
            }

            if (false === mb_strpos($realPath, __DIR__))
            {
                continue;
            }

            try
            {
                $reflectionClass = new \ReflectionClass($className);
            }
            catch (\ReflectionException $e)
            {
                return;
            }

            foreach ($annotationReader->getClassAnnotations($reflectionClass) as $annotation)
            {
                if (!$annotation instanceof Subscriber)
                {
                    continue;
                }

                foreach ($reflectionClass->getMethods() as $method)
                {
                    if ('Event' !== mb_substr($method->getName(), -5))
                    {
                        continue;
                    }

                    if ($annotation = $annotationReader->getMethodAnnotation($method, Listen::class))
                    {
                        $eventName = $annotation->event;
                    }
                    else
                    {
                        $methodParameter = current($method->getParameters());

                        if (!$methodParameter->hasType())
                        {
                            continue;
                        }

                        $eventType = ClassHelper::getClassNameDot($methodParameter->getType()->getName());
                        $eventName = str_replace('.event', '', $eventType);
                    }

                    $definition = $container->getDefinition($className);
                    $definition->addTag(
                        'kernel.event_listener',
                        [
                            'event'  => $eventName,
                            'method' => $method->getName(),
                        ]
                    );
                }
            }

            foreach ($reflectionClass->getProperties() as $property)
            {
                foreach ($annotationReader->getPropertyAnnotations($property) as $annotation)
                {
                    if (!$annotation instanceof Dependency)
                    {
                        continue;
                    }

                    $definition = $container->getDefinition($className);
                    $dependency = $property->getType()->getName();
                    $reference  = new Reference($dependency);

                    if ($property->isPublic())
                    {
                        $definition->setProperty($property->getName(), $reference);

                        continue;
                    }

                    $methodCall = 'set' . ClassHelper::getClassName($dependency);

                    if ($reflectionClass->hasMethod($methodCall))
                    {
                        $definition->addMethodCall($methodCall, [$reference]);

                        continue;
                    }

                    throw new \RuntimeException(sprintf('Property "%s" is private or the method "%s" does not exist in class "%s"', $property->getName(), $methodCall, $className));
                }
            }
        }
    }
}
