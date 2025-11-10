<?php

namespace App\Exceptions;

use Exception;

class DuplicateSubscriptionException extends Exception
{
    public $subscription;

    public function __construct(string $message = "", $subscription = null, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->subscription = $subscription;
    }
}
