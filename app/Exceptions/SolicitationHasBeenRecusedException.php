<?php

namespace App\Exceptions;

use Exception;

class SolicitationHasBeenRecusedException extends Exception
{
    protected $message = "SolicitationHasBeenRecusedException";
    protected $code = 400;
}
