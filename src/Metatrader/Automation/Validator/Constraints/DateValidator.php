<?php

declare(strict_types=1);

namespace App\Metatrader\Automation\Validator\Constraints;

use App\Metatrader\Automation\Helper\ClassHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateValidator extends ConstraintValidator
{
    public const MINIMUM_DATE = '2013-01-01';

    /**
     * @param string $value
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value))
        {
            return;
        }

        if (!$constraint instanceof Date)
        {
            throw new UnexpectedTypeException($constraint, Date::class);
        }

        $object = $this->context->getObject();

        if (!ClassHelper::hasProperty($object, $constraint->fromField))
        {
            $violation = $this->context->buildViolation($constraint->missingProperty);
            $violation->setParameter('{{ property }}', $constraint->fromField);
            $violation->setParameter('{{ object }}', get_class($object));
            $violation->addViolation();

            return;
        }

        if (!ClassHelper::hasProperty($object, $constraint->toField))
        {
            $violation = $this->context->buildViolation($constraint->missingProperty);
            $violation->setParameter('{{ property }}', $constraint->toField);
            $violation->setParameter('{{ object }}', get_class($object));
            $violation->addViolation();

            return;
        }

        $name    = $this->context->getPropertyName();
        $from    = ClassHelper::getPropertyValue($object, $constraint->fromField);
        $to      = ClassHelper::getPropertyValue($object, $constraint->toField);
        $today   = new \DateTime('today midnight');
        $minimum = \DateTime::createFromFormat($constraint->format, self::MINIMUM_DATE);

        if ($constraint->fromField === $name)
        {
            if ($from >= $to)
            {
                $violation = $this->context->buildViolation($constraint->dateLowerThan);
                $violation->setParameter('{{ date }}', $from->format($constraint->format));
                $violation->setParameter('{{ than }}', $to->format($constraint->format));
                $violation->addViolation();
            }
            elseif ($from >= $today)
            {
                $violation = $this->context->buildViolation($constraint->dateLowerThan);
                $violation->setParameter('{{ date }}', $from->format($constraint->format));
                $violation->setParameter('{{ than }}', $today->format($constraint->format));
                $violation->addViolation();
            }
            elseif ($from < $minimum)
            {
                $violation = $this->context->buildViolation($constraint->dateGreaterThan);
                $violation->setParameter('{{ date }}', $from->format($constraint->format));
                $violation->setParameter('{{ than }}', $minimum->format($constraint->format));
                $violation->addViolation();
            }
        }
        elseif ($constraint->toField === $name)
        {
            if ($to >= $today)
            {
                $violation = $this->context->buildViolation($constraint->dateLowerThan);
                $violation->setParameter('{{ date }}', $to->format($constraint->format));
                $violation->setParameter('{{ than }}', $today->format($constraint->format));
                $violation->addViolation();
            }
        }
    }
}
