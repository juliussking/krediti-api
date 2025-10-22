<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class ValueBelowMinimumException extends Exception
{
    use RenderToJson;

    protected $message = 'Value is below the minimum fator!';
    protected $code = 400;
}
