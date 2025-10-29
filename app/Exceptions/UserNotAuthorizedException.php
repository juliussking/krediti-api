<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class UserNotAuthorizedException extends Exception
{
    use RenderToJson;
    protected $message = "User not authorized";
    protected $code = 400;

    
}
