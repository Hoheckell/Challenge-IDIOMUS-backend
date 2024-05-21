<?php

namespace App\Exceptions;

use Exception;

class InvalidPlayerPositionException extends Exception
{
    public function __construct($position, $code = 400)
    {
        $message = "Invalid value for position: {$position}";
        parent::__construct($message, $code);
    }
}
