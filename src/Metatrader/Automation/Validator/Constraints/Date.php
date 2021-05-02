<?php

namespace App\Metatrader\Automation\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * Class Date
 *
 * @package App\Metatrader\Automation\Validator\Constraints
 */
class Date extends Constraint
{
    /**
     * @var string
     */
    public string $missingProperty = 'The property "{{ property }}" is missing in "{{ object }}" to validate.';

    /**
     * @var string
     */
    public string $dateGreaterThan = 'The date "{{ date }}" must be greater than "{{ than }}".';

    /**
     * @var string
     */
    public string $dateLowerThan = 'The date "{{ date }}" must be lower than "{{ than }}".';

    /**
     * @var string
     */
    public string $fromField = 'from';

    /**
     * @var string
     */
    public string $toField = 'to';

    /**
     * @var string
     */
    public string $format;

    /**
     * @return string
     */
    public function getDefaultOption(): string
    {
        return 'format';
    }

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['format'];
    }
}
