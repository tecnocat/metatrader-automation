<?php

declare(strict_types=1);

namespace App;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Helper\ClassHelper;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $this->loadEventSubscribers($container);
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

    private function loadEventSubscribers(ContainerBuilder $container): void
    {
        $annotationReader = new AnnotationReader();
        $classMap         = require __DIR__ . '/../vendor/composer/autoload_classmap.php';

        foreach ($classMap as $className => $classFile)
        {
            if (false === mb_strpos(realpath($classFile), __DIR__))
            {
                continue;
            }

            try
            {
                $reflectionClass = new \ReflectionClass($className);
            }
            catch (\ReflectionException $e)
            {
                continue;
            }

            foreach ($annotationReader->getClassAnnotations($reflectionClass) as $annotation)
            {
                if (!$annotation instanceof Subscriber)
                {
                    continue;
                }

                $id         = 'app.' . ClassHelper::getClassNameUnderscore($reflectionClass);
                $definition = $container->autowire($id, $className);

                foreach ($reflectionClass->getMethods() as $method)
                {
                    if ('Event' !== substr($method->getName(), -5))
                    {
                        continue;
                    }

                    foreach ($method->getParameters() as $methodParameter)
                    {
                        if (!$methodParameter->hasType())
                        {
                            continue;
                        }

                        try
                        {
                            $eventType = new \ReflectionClass($methodParameter->getType()->getName());
                            $eventName = ClassHelper::getClassNameDotted($eventType);
                        }
                        catch (\Exception $e)
                        {
                            continue;
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
            }
        }
    }
}
