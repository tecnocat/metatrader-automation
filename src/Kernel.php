<?php

namespace App;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Helper\ClassTools;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use function dirname;

/**
 * Class Kernel
 *
 * @package App
 */
class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $annotationReader = new AnnotationReader();
        $classMap         = require __DIR__ . '/../vendor/composer/autoload_classmap.php';

        foreach ($classMap as $className => $classFile)
        {
            if (false === strpos(realpath($classFile), __DIR__))
            {
                continue;
            }

            $reflectionClass = new \ReflectionClass($className);

            foreach ($annotationReader->getClassAnnotations($reflectionClass) as $annotation)
            {
                if (!$annotation instanceof Subscriber)
                {
                    continue;
                }

                $id         = 'app.' . ClassTools::getClassNameUnderscore($reflectionClass);
                $definition = $container->autowire($id, $className);

                foreach ($reflectionClass->getMethods() as $method)
                {
                    if (!preg_match('/^on(.*)Event$/', $method->getName(), $matches))
                    {
                        continue;
                    }

                    $event = str_replace('.subscriber', '', ClassTools::getClassNameDotted($reflectionClass));
                    $definition->addTag(
                        'kernel.event_listener',
                        [
                            'event'  => $event . '.' . ClassTools::getCamelCaseDotted($matches[1]),
                            'method' => $method->getName(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param ContainerConfigurator $container
     */
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

    /**
     * @param RoutingConfigurator $routes
     */
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
}
