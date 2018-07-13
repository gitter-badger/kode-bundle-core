<?php

namespace KodeCms\KodeBundle\Core\Exception;

use Throwable;
use Exception;

class ErrorException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
