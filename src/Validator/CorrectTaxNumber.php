<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CorrectTaxNumber extends Constraint
{
    public string $message = 'Tax Number must be valid';
}
