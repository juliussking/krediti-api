<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class ExceedsTotalException extends Exception
{
    use RenderToJson;

    protected $message = 'Value exceeded the total!';
    protected $code = 400;
}
