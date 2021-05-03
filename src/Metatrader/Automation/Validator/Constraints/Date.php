<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Date extends Constraint
{
    public string $dateGreaterThan = 'The date "{{ date }}" must be greater than "{{ than }}".';
    public string $dateLowerThan   = 'The date "{{ date }}" must be lower than "{{ than }}".';
    public string $format;
    public string $fromField       = 'from';
    public string $missingProperty = 'The property "{{ property }}" is missing in "{{ object }}" to validate.';
    public string $toField         = 'to';

    public function getDefaultOption(): string
    {
        return 'format';
    }

    public function getRequiredOptions(): array
    {
        return ['format'];
    }
}
