<?php

namespace App\Exception;

class StripePaymentException extends \RuntimeException
{
    public function __construct(string $message = 'Stripe Payment Request Exception', int $code = 400, \Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}
