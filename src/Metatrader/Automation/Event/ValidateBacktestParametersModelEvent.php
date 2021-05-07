<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Event;

use App\Metatrader\Automation\Model\AbstractModel;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidateBacktestParametersModelEvent extends AbstractEvent
{
    private AbstractModel                    $model;
    private string                           $modelClass;
    private array                            $parameters;
    private ConstraintViolationListInterface $violations;

    public function __construct(string $modelClass, array $parameters)
    {
        $this->modelClass = $modelClass;
        $this->parameters = $parameters;
    }

    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    public function setModel(AbstractModel $model): void
    {
        $this->model = $model;
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): void
    {
        $this->violations = $violations;
    }

    public function isValid(): bool
    {
        return 0 === $this->violations->count();
    }
}
