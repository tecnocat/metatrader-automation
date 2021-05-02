<?php

namespace App\Metatrader\Automation\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * Class ExpertAdvisor
 *
 * @package App\Metatrader\Automation\Validator\Constraints
 */
class ExpertAdvisor extends Constraint
{
    /**
     * @var string
     */
    public string $missingExpertAdvisor = 'The Expert Advisor "{{ expert_advisor }}" is missing or not implemented.';
}