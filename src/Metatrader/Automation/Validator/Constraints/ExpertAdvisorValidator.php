<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Validator\Constraints;

use App\Metatrader\Automation\ExpertAdvisor\AbstractExpertAdvisor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExpertAdvisorValidator extends ConstraintValidator
{
    /**
     * @param string $value
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value))
        {
            return;
        }

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
