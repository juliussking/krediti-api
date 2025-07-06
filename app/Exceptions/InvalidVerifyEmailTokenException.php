<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class InvalidVerifyEmailTokenException extends Exception
{
    use RenderToJson;

    protected $message = 'Invalid Verify Email Token!';
    protected $code = 400;
}
