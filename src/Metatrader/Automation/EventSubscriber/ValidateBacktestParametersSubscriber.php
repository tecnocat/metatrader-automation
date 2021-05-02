<?php

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\ValidateBacktestParametersModelEvent;
use App\Metatrader\Automation\Helper\ClassTools;
use App\Metatrader\Automation\Model\AbstractModel;
use App\Metatrader\Automation\Model\BacktestModel;
use ReflectionException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidateBacktestParametersSubscriber
 *
 * @Subscriber
 *
 * @package App\Metatrader\Automation\EventSubscriber
 */
class ValidateBacktestParametersSubscriber
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ValidateBacktestParametersModelEvent $event
     *
     * @throws ReflectionException
     */
    public function onModelEvent(ValidateBacktestParametersModelEvent $event): void
    {
        // TODO: Make a generic AbstractModel validator?
        $model  = $this->getModel(BacktestModel::class, $event->getParameters());
        $errors = $this->validator->validate($model);
        $event->setModel($model);
        $event->setErrors($errors);
    }

    /**
     * @param string $model
     * @param array  $parameters
     *
     * @return AbstractModel
     * @throws ReflectionException
     */
    private function getModel(string $model, array $parameters): AbstractModel
    {
        $instance = new $model();

        foreach ($parameters as $parameterName => $parameterValue)
        {
            if (ClassTools::hasProperty($instance, $parameterName))
            {
                ClassTools::setPropertyValue($instance, $parameterName, $parameterValue);
            }
        }

        return $instance;
    }
}