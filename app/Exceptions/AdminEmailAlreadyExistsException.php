<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class AdminEmailAlreadyExistsException extends Exception
{
    use RenderToJson;

    protected $message = 'Admin email already registered!';
    protected $code = 400;
    
}
