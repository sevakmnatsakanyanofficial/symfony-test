<?php

namespace App\Exception;

class PaypalPaymentException extends \RuntimeException
{
    public function __construct(string $message = 'Paypal Payment Request Exception', int $code = 400, \Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}
