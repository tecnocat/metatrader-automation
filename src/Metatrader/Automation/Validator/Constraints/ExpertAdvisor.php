<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExpertAdvisor extends Constraint
{
    public string $missingExpertAdvisor = 'The Expert Advisor "{{ expert_advisor }}" is missing or not implemented.';
}
