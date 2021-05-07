<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\EventSubscriber;

use App\Metatrader\Automation\Annotation\Subscriber;
use App\Metatrader\Automation\Event\ValidateBacktestParametersModelEvent;
use App\Metatrader\Automation\Helper\ClassTools;
use App\Metatrader\Automation\Model\AbstractModel;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Subscriber
 */
class ValidateBacktestParametersSubscriber
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function onModelEvent(ValidateBacktestParametersModelEvent $event): void
    {
        $model      = $this->getModel($event->getModelClass(), $event->getParameters());
        $violations = $this->validator->validate($model);
        $event->setModel($model);
        $event->setViolations($violations);
    }

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
