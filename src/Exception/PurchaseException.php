<?php

namespace App\Exception;

class PurchaseException extends \RuntimeException
{
    public function __construct(string $message = '', int $code = 400, \Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }
}
