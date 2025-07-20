<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class EmailIsNotVerifiedException extends Exception
{
    use RenderToJson;

    protected $message = 'Email is not verified!';
    protected $code = 400;
    
}
