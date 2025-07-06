<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class EmailAlreadyVerifiedException extends Exception
{
    use RenderToJson;

    protected $message = 'Email already verified!';
    protected $code = 400;
    
}
