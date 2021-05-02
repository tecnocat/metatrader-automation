<?php

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Model\AbstractModel;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidateBacktestParametersEvent
 *
 * @package App\Metatrader\Automation\Event
 */
class ValidateBacktestParametersModelEvent extends AbstractEvent
{
    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $errors;

    /**
     * @var AbstractModel
     */
    private AbstractModel $model;

    /**
     * ValidateBacktestParametersEvent constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return 0 === $this->errors->count();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }

    /**
     * @param ConstraintViolationListInterface $errors
     */
    public function setErrors(ConstraintViolationListInterface $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return AbstractModel
     */
    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    /**
     * @param AbstractModel $model
     */
    public function setModel(AbstractModel $model): void
    {
        $this->model = $model;
    }
}