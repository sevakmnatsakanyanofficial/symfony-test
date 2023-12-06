<?php

namespace App\Validator;

use App\Helper\TaxHelper;
use Symfony\Component\HttpFoundation\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CorrectTaxNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CorrectTaxNumber) {
            throw new UnexpectedTypeException($constraint, CorrectTaxNumber::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!preg_match(TaxHelper::GERMANY_NUMBER_FORMAT_PATTERN, $value)
            && !preg_match(TaxHelper::ITALY_NUMBER_FORMAT_PATTERN, $value)
            && !preg_match(TaxHelper::GREECE_NUMBER_FORMAT_PATTERN, $value)
            && !preg_match(TaxHelper::FRANCE_NUMBER_FORMAT_PATTERN, $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
