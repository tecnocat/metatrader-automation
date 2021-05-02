<?php

namespace App\Metatrader\Automation\Validator\Constraints;

use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ExpertAdvisorValidator
 *
 * @package App\Metatrader\Automation\Validator\Constraints
 */
class ExpertAdvisorValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ExpertAdvisor)
        {
            throw new UnexpectedTypeException($constraint, ExpertAdvisor::class);
        }

        if (!class_exists(AbstractExpertAdvisor::getExpertAdvisorClass($value)))
        {
            $violation = $this->context->buildViolation($constraint->missingExpertAdvisor);
            $violation->setParameter('{{ expert_advisor }}', $value);
            $violation->addViolation();
        }
    }
}